<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class JobSeekerGoogleController extends Controller
{
    public function redirect()
    {
        session(['google_intent' => 'jobseeker']);
        return Socialite::driver('google')->redirect();
    }

    public function handleCallback($googleUser)
    {
        // Try existing user by google_id + user_type
        $existing = User::where('google_id', $googleUser->getId())
            ->where('user_type', 'user')
            ->first();

        if ($existing) {
            $existing->update(['last_login_at' => now()]);
            Auth::login($existing);
            return redirect()->route('jobseeker.dashboard');
        }

        // Check if email exists as job seeker without google_id → link account
        $byEmail = User::where('email', $googleUser->getEmail())
            ->where('user_type', 'user')
            ->whereNull('google_id')
            ->first();

        if ($byEmail) {
            $byEmail->update([
                'google_id' => $googleUser->getId(),
                'last_login_at' => now(),
                'email_verified_at' => $byEmail->email_verified_at ?? now(),
            ]);
            Auth::login($byEmail);
            return redirect()->route('jobseeker.dashboard');
        }

        // New user → create account directly
        $user = User::create([
            'name' => $googleUser->getName(),
            'email' => $googleUser->getEmail(),
            'password' => null,
            'user_type' => 'user',
            'auth_provider' => 'google',
            'google_id' => $googleUser->getId(),
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        Auth::login($user);
        return redirect()->route('jobseeker.dashboard');
    }
}
