<?php

namespace App\Http\Controllers\Api\Auth;

use App\Enums\UserStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class JobSeekerAuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone'    => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = User::create([
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'phone'     => $validated['phone'] ?? null,
            'password'  => $validated['password'],
            'user_type' => 'user',
            'status'    => UserStatusEnum::Active,
            'is_active' => true,
        ]);

        $token = $user->createToken('jobseeker-app')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Registration successful.',
            'data'    => [
                'user'  => $this->formatUser($user),
                'token' => $token,
            ],
        ], 201);
    }

    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $validated['email'])
            ->where('user_type', 'user')
            ->first();

        if (! $user || ! Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials.',
            ], 401);
        }

        if ($user->status === UserStatusEnum::Suspended) {
            return response()->json([
                'success' => false,
                'message' => 'Your account has been suspended. Please contact support.',
            ], 403);
        }

        $user->update(['last_login_at' => now()]);

        $token = $user->createToken('jobseeker-app')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login successful.',
            'data'    => [
                'user'  => $this->formatUser($user),
                'token' => $token,
            ],
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully.',
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        $user = $request->user()->load('jobSeekerProfile');

        return response()->json([
            'success' => true,
            'data'    => $this->formatUser($user),
        ]);
    }

    private function formatUser(User $user): array
    {
        return [
            'id'                   => $user->id,
            'name'                 => $user->name,
            'email'                => $user->email,
            'phone'                => $user->phone,
            'status'               => $user->status?->value,
            'is_profile_completed' => $user->is_profile_completed,
            'email_verified_at'    => $user->email_verified_at,
            'last_login_at'        => $user->last_login_at,
            'created_at'           => $user->created_at,
            'profile'              => $user->relationLoaded('jobSeekerProfile') && $user->jobSeekerProfile
                ? [
                    'first_name'          => $user->jobSeekerProfile->first_name,
                    'last_name'           => $user->jobSeekerProfile->last_name,
                    'salutation'          => $user->jobSeekerProfile->salutation,
                    'designation'         => $user->jobSeekerProfile->designation,
                    'profile_photo_url'   => $user->jobSeekerProfile->getProfilePictureUrl(),
                    'profile_completeness'=> $user->jobSeekerProfile->profile_completeness,
                    'is_open_to_work'     => $user->jobSeekerProfile->is_open_to_work,
                ]
                : null,
        ];
    }
}
