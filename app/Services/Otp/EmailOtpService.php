<?php

namespace App\Services\Otp;

use App\Models\EmailOtpSession;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class EmailOtpService
{
    public function generateOtp(string $email, string $purpose): string
    {
        $otp = str_pad((string) random_int(100000, 999999), 6, '0', STR_PAD_LEFT);

        EmailOtpSession::where('email', $email)
            ->where('purpose', $purpose)
            ->whereNull('verified_at')
            ->delete();

        EmailOtpSession::create([
            'email' => $email,
            'otp_code' => Hash::make($otp),
            'purpose' => $purpose,
            'expires_at' => now()->addMinutes(10),
            'attempts' => 0,
            'resend_count' => 0,
        ]);

        return $otp;
    }

    public function sendOtp(string $email, string $otp, string $purpose): void
    {
        Mail::send('emails.otp', [
            'otp' => $otp,
            'purpose' => $purpose,
        ], function ($message) use ($email) {
            $message->to($email)
                ->subject('Your Vaidyog Verification Code');
        });
    }

    public function verifyOtp(string $email, string $otp, string $purpose): bool
    {
        $session = EmailOtpSession::where('email', $email)
            ->where('purpose', $purpose)
            ->whereNull('verified_at')
            ->latest()
            ->first();

        if (!$session) {
            return false;
        }

        if ($session->isExpired()) {
            return false;
        }

        if ($session->attempts >= 5) {
            return false;
        }

        $session->increment('attempts');

        if (!Hash::check($otp, $session->otp_code)) {
            return false;
        }

        $session->update(['verified_at' => now()]);
        return true;
    }

    public function canResend(string $email, string $purpose): bool
    {
        $count = EmailOtpSession::where('email', $email)
            ->where('purpose', $purpose)
            ->where('created_at', '>=', now()->subHour())
            ->count();

        return $count < 3;
    }

    public function invalidateSession(string $email, string $purpose): void
    {
        EmailOtpSession::where('email', $email)
            ->where('purpose', $purpose)
            ->whereNull('verified_at')
            ->delete();
    }
}
