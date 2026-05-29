<?php

namespace App\Livewire\Frontend\Auth;

use App\Models\User;
use App\Services\Otp\PhoneOtpService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.guest')]
class JobSeekerLogin extends Component
{
    // Login method: 'email' or 'phone'
    public string $loginMethod = 'email';

    // Email/Password fields
    public string $email = '';
    public string $password = '';

    // Phone OTP fields
    public string $phone = '';
    public string $otp = '';
    public bool $otpSent = false;
    public int $resendCooldown = 0;

    // Shared
    public string $error = '';

    public function updatedLoginMethod(): void
    {
        $this->reset(['email', 'password', 'phone', 'otp', 'otpSent', 'resendCooldown', 'error']);
    }

    /*
    |--------------------------------------------------------------------------
    | Email + Password Login
    |--------------------------------------------------------------------------
    */

    public function loginWithEmail(): void
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        $user = User::where('email', $this->email)
            ->where('user_type', 'user')
            ->first();

        if (!$user || !$user->password || !Hash::check($this->password, $user->password)) {
            $this->error = 'Invalid email or password.';
            return;
        }

        if (!$user->is_active) {
            $this->error = 'Your account has been deactivated. Please contact support.';
            return;
        }

        $user->update(['last_login_at' => now()]);
        Auth::login($user);
        $this->redirect(route('jobseeker.dashboard'), navigate: true);
    }

    /*
    |--------------------------------------------------------------------------
    | Phone OTP Login
    |--------------------------------------------------------------------------
    */

    public function sendPhoneOtp(): void
    {
        $this->validate([
            'phone' => 'required|string|digits:10',
        ]);

        $user = User::where('phone', $this->phone)
            ->where('user_type', 'user')
            ->first();

        if (!$user) {
            $this->error = 'No account found with this phone number.';
            return;
        }

        if (!$user->is_active) {
            $this->error = 'Your account has been deactivated. Please contact support.';
            return;
        }

        $service = app(PhoneOtpService::class);

        if (!$service->canResend($this->phone, 'jobseeker_login')) {
            $this->error = 'Too many OTP requests. Please try again later.';
            return;
        }

        $otp = $service->generateOtp($this->phone, 'jobseeker_login');
        $service->sendOtp($this->phone, $otp, 'jobseeker_login');

        $this->otpSent = true;
        $this->error = '';
        $this->resendCooldown = 60;
    }

    public function resendOtp(): void
    {
        if ($this->resendCooldown > 0) return;
        $this->sendPhoneOtp();
    }

    public function verifyPhoneOtp(): void
    {
        $this->validate([
            'otp' => 'required|string|size:6',
        ]);

        $service = app(PhoneOtpService::class);
        $verified = $service->verifyOtp($this->phone, $this->otp, 'jobseeker_login');

        if (!$verified) {
            $this->error = 'Invalid or expired OTP. Please try again.';
            return;
        }

        $user = User::where('phone', $this->phone)
            ->where('user_type', 'user')
            ->first();

        if (!$user || !$user->is_active) {
            $this->error = 'Account not found or deactivated.';
            return;
        }

        $user->update([
            'last_login_at' => now(),
            'phone_verified_at' => $user->phone_verified_at ?? now(),
        ]);

        Auth::login($user);
        $this->redirect(route('jobseeker.dashboard'), navigate: true);
    }

    public function render()
    {
        return view('livewire.frontend.auth.job-seeker-login');
    }
}
