<?php

namespace App\Http\Controllers\Api\Auth;

use App\Enums\UserStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\MedicalInstitution;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RecruiterAuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone'    => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $recruiter = MedicalInstitution::create([
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'phone'     => $validated['phone'] ?? null,
            'password'  => $validated['password'],
            'user_type' => 'MedicalInstitution',
            'status'    => UserStatusEnum::Active,
            'is_active' => true,
        ]);

        $token = $recruiter->createToken('recruiter-app')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Registration successful.',
            'data'    => [
                'recruiter' => $this->formatRecruiter($recruiter),
                'token'     => $token,
            ],
        ], 201);
    }

    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $recruiter = MedicalInstitution::withoutGlobalScopes()
            ->where('email', $validated['email'])
            ->where('user_type', 'MedicalInstitution')
            ->first();

        if (! $recruiter || ! Hash::check($validated['password'], $recruiter->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials.',
            ], 401);
        }

        if ($recruiter->status === UserStatusEnum::Suspended) {
            return response()->json([
                'success' => false,
                'message' => 'Your account has been suspended. Please contact support.',
            ], 403);
        }

        $recruiter->update(['last_login_at' => now()]);

        $token = $recruiter->createToken('recruiter-app')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login successful.',
            'data'    => [
                'recruiter' => $this->formatRecruiter($recruiter->load('profile')),
                'token'     => $token,
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
        $recruiter = $request->user()->load('profile');

        return response()->json([
            'success' => true,
            'data'    => $this->formatRecruiter($recruiter),
        ]);
    }

    private function formatRecruiter(MedicalInstitution $recruiter): array
    {
        $profile = $recruiter->profile ?? null;

        return [
            'id'                   => $recruiter->id,
            'name'                 => $recruiter->name,
            'email'                => $recruiter->email,
            'phone'                => $recruiter->phone,
            'status'               => $recruiter->status?->value,
            'is_profile_completed' => $recruiter->is_profile_completed,
            'email_verified_at'    => $recruiter->email_verified_at,
            'last_login_at'        => $recruiter->last_login_at,
            'created_at'           => $recruiter->created_at,
            'profile'              => $profile ? [
                'institution_name'     => $profile->institution_name,
                'slug'                 => $profile->slug,
                'med_type'             => $profile->med_type?->value,
                'city'                 => $profile->city,
                'state'                => $profile->state,
                'logo_url'             => $profile->logo_path ? asset('storage/' . $profile->logo_path) : null,
                'is_profile_completed' => $profile->is_profile_completed,
            ] : null,
        ];
    }
}
