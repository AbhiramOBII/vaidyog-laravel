<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\MedicalInstitution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class RecruiterGoogleController extends Controller
{
    public function redirect()
    {
        session(['google_intent' => 'recruiter']);
        return Socialite::driver('google')->redirect();
    }

    public function callback(Request $request)
    {
        $googleUser = Socialite::driver('google')->user();

        // Try existing user by google_id + user_type
        $existing = MedicalInstitution::withoutGlobalScopes()
            ->where('google_id', $googleUser->getId())
            ->where('user_type', 'MedicalInstitution')
            ->first();

        if ($existing) {
            $existing->update(['last_login_at' => now()]);
            Auth::login($existing);
            return redirect('/recruiter/dashboard');
        }

        // Check if email exists as MedicalInstitution without google_id → offer link
        $byEmail = MedicalInstitution::withoutGlobalScopes()
            ->where('email', $googleUser->getEmail())
            ->where('user_type', 'MedicalInstitution')
            ->whereNull('google_id')
            ->first();

        if ($byEmail) {
            $byEmail->update([
                'google_id' => $googleUser->getId(),
                'last_login_at' => now(),
                'email_verified_at' => $byEmail->email_verified_at ?? now(),
            ]);
            Auth::login($byEmail);
            return redirect('/recruiter/dashboard');
        }

        // New user → store in session and redirect to onboarding
        session([
            'google_recruiter_data' => [
                'google_id' => $googleUser->getId(),
                'email' => $googleUser->getEmail(),
                'name' => $googleUser->getName(),
                'avatar' => $googleUser->getAvatar(),
            ],
        ]);

        return redirect()->route('recruiter.google.onboarding');
    }
}
