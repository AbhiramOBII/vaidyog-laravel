<?php

namespace App\Services\Otp;

use App\Models\PhoneOtpSession;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class PhoneOtpService
{
    public function generateOtp(string $phone, string $purpose): string
    {
        $otp = str_pad((string) random_int(100000, 999999), 6, '0', STR_PAD_LEFT);

        PhoneOtpSession::where('phone', $phone)
            ->where('purpose', $purpose)
            ->whereNull('verified_at')
            ->delete();

        PhoneOtpSession::create([
            'phone' => $phone,
            'otp_code' => Hash::make($otp),
            'purpose' => $purpose,
            'expires_at' => now()->addMinutes(10),
            'attempts' => 0,
            'resend_count' => 0,
        ]);

        return $otp;
    }

    public function sendOtp(string $phone, string $otp, string $purpose): void
    {
        // TODO: Integrate with SMS gateway (MSG91, Twilio, etc.)
        // For now, log the OTP for development
        Log::info("Phone OTP for {$phone} [{$purpose}]: {$otp}");
    }

    public function verifyOtp(string $phone, string $otp, string $purpose): bool
    {
        $session = PhoneOtpSession::where('phone', $phone)
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

    public function canResend(string $phone, string $purpose): bool
    {
        $count = PhoneOtpSession::where('phone', $phone)
            ->where('purpose', $purpose)
            ->where('created_at', '>=', now()->subHour())
            ->count();

        return $count < 3;
    }

    public function invalidateSession(string $phone, string $purpose): void
    {
        PhoneOtpSession::where('phone', $phone)
            ->where('purpose', $purpose)
            ->whereNull('verified_at')
            ->delete();
    }
}
