<?php

namespace Tests\Unit;

use App\Models\PhoneOtpSession;
use App\Services\Otp\PhoneOtpService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PhoneOtpServiceTest extends TestCase
{
    use RefreshDatabase;

    private PhoneOtpService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new PhoneOtpService();
    }

    public function test_generate_otp_creates_session(): void
    {
        $otp = $this->service->generateOtp('9876543210', 'test_purpose');

        $this->assertNotEmpty($otp);
        $this->assertEquals(6, strlen($otp));
        $this->assertDatabaseHas('phone_otp_sessions', [
            'phone' => '9876543210',
            'purpose' => 'test_purpose',
        ]);
    }

    public function test_generate_otp_deletes_previous_unverified_sessions(): void
    {
        $this->service->generateOtp('9876543210', 'test_purpose');
        $this->service->generateOtp('9876543210', 'test_purpose');

        $count = PhoneOtpSession::where('phone', '9876543210')
            ->where('purpose', 'test_purpose')
            ->count();

        $this->assertEquals(1, $count);
    }

    public function test_verify_otp_returns_true_for_valid_otp(): void
    {
        $otp = $this->service->generateOtp('9876543210', 'test_purpose');

        $result = $this->service->verifyOtp('9876543210', $otp, 'test_purpose');

        $this->assertTrue($result);
    }

    public function test_verify_otp_returns_false_for_wrong_otp(): void
    {
        $this->service->generateOtp('9876543210', 'test_purpose');

        $result = $this->service->verifyOtp('9876543210', '000000', 'test_purpose');

        $this->assertFalse($result);
    }

    public function test_verify_otp_returns_false_for_expired_session(): void
    {
        PhoneOtpSession::create([
            'phone' => '9876543210',
            'otp_code' => Hash::make('123456'),
            'purpose' => 'test_purpose',
            'expires_at' => now()->subMinutes(1),
            'attempts' => 0,
            'resend_count' => 0,
        ]);

        $result = $this->service->verifyOtp('9876543210', '123456', 'test_purpose');

        $this->assertFalse($result);
    }

    public function test_verify_otp_returns_false_after_max_attempts(): void
    {
        PhoneOtpSession::create([
            'phone' => '9876543210',
            'otp_code' => Hash::make('123456'),
            'purpose' => 'test_purpose',
            'expires_at' => now()->addMinutes(10),
            'attempts' => 5,
            'resend_count' => 0,
        ]);

        $result = $this->service->verifyOtp('9876543210', '123456', 'test_purpose');

        $this->assertFalse($result);
    }

    public function test_verify_otp_returns_false_for_wrong_phone(): void
    {
        $otp = $this->service->generateOtp('9876543210', 'test_purpose');

        $result = $this->service->verifyOtp('9999999999', $otp, 'test_purpose');

        $this->assertFalse($result);
    }

    public function test_verify_otp_returns_false_for_wrong_purpose(): void
    {
        $otp = $this->service->generateOtp('9876543210', 'test_purpose');

        $result = $this->service->verifyOtp('9876543210', $otp, 'wrong_purpose');

        $this->assertFalse($result);
    }

    public function test_verified_otp_cannot_be_reused(): void
    {
        $otp = $this->service->generateOtp('9876543210', 'test_purpose');

        $this->assertTrue($this->service->verifyOtp('9876543210', $otp, 'test_purpose'));
        $this->assertFalse($this->service->verifyOtp('9876543210', $otp, 'test_purpose'));
    }

    public function test_can_resend_returns_true_when_under_limit(): void
    {
        $this->assertTrue($this->service->canResend('9876543210', 'test_purpose'));
    }

    public function test_can_resend_returns_false_when_limit_exceeded(): void
    {
        // Create 3 sessions within the last hour
        for ($i = 0; $i < 3; $i++) {
            PhoneOtpSession::create([
                'phone' => '9876543210',
                'otp_code' => Hash::make('123456'),
                'purpose' => 'test_purpose',
                'expires_at' => now()->addMinutes(10),
                'attempts' => 0,
                'resend_count' => 0,
            ]);
        }

        $this->assertFalse($this->service->canResend('9876543210', 'test_purpose'));
    }

    public function test_invalidate_session_removes_unverified(): void
    {
        $this->service->generateOtp('9876543210', 'test_purpose');

        $this->assertDatabaseHas('phone_otp_sessions', [
            'phone' => '9876543210',
            'purpose' => 'test_purpose',
        ]);

        $this->service->invalidateSession('9876543210', 'test_purpose');

        $this->assertDatabaseMissing('phone_otp_sessions', [
            'phone' => '9876543210',
            'purpose' => 'test_purpose',
            'verified_at' => null,
        ]);
    }

    public function test_otp_increments_attempts_on_wrong_code(): void
    {
        $this->service->generateOtp('9876543210', 'test_purpose');

        $this->service->verifyOtp('9876543210', '000000', 'test_purpose');

        $session = PhoneOtpSession::where('phone', '9876543210')
            ->where('purpose', 'test_purpose')
            ->first();

        $this->assertEquals(1, $session->attempts);
    }
}
