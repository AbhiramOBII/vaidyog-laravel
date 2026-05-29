<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class GoogleCallbackController extends Controller
{
    public function __invoke(Request $request)
    {
        $googleUser = Socialite::driver('google')->user();
        $intent = session('google_intent', 'jobseeker');
        session()->forget('google_intent');

        if ($intent === 'recruiter') {
            return app(RecruiterGoogleController::class)->handleCallback($googleUser);
        }

        return app(JobSeekerGoogleController::class)->handleCallback($googleUser);
    }
}
