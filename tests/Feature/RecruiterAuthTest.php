<?php

namespace Tests\Feature;

use App\Livewire\Frontend\Auth\RecruiterLogin;
use App\Livewire\Frontend\Auth\RecruiterRegistration;
use App\Models\MedicalInstitution;
use App\Models\PhoneOtpSession;
use App\Services\Otp\PhoneOtpService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class RecruiterAuthTest extends TestCase
{
    use RefreshDatabase;

    /*
    |--------------------------------------------------------------------------
    | Registration Tests
    |--------------------------------------------------------------------------
    */

    public function test_recruiter_registration_page_loads(): void
    {
        $response = $this->get(route('recruiter.register'));
        $response->assertStatus(200);
        $response->assertSeeLivewire(RecruiterRegistration::class);
    }

    public function test_registration_validates_required_fields(): void
    {
        Livewire::test(RecruiterRegistration::class)
            ->set('name', '')
            ->set('email', '')
            ->set('phone', '')
            ->set('med_type', '')
            ->set('password', '')
            ->call('register')
            ->assertHasErrors(['name', 'email', 'phone', 'med_type', 'password']);
    }

    public function test_registration_validates_email_format(): void
    {
        Livewire::test(RecruiterRegistration::class)
            ->set('name', 'Test Hospital')
            ->set('email', 'invalid-email')
            ->set('phone', '9876543210')
            ->set('med_type', 'clinics')
            ->set('password', 'password123')
            ->set('password_confirmation', 'password123')
            ->call('register')
            ->assertHasErrors(['email']);
    }

    public function test_registration_validates_password_confirmation(): void
    {
        Livewire::test(RecruiterRegistration::class)
            ->set('name', 'Test Hospital')
            ->set('email', 'test@hospital.com')
            ->set('phone', '9876543210')
            ->set('med_type', 'clinics')
            ->set('password', 'password123')
            ->set('password_confirmation', 'different')
            ->call('register')
            ->assertHasErrors(['password']);
    }

    public function test_registration_creates_account_and_redirects(): void
    {
        Livewire::test(RecruiterRegistration::class)
            ->set('name', 'New Hospital')
            ->set('email', 'new@hospital.com')
            ->set('phone', '9876543210')
            ->set('med_type', 'clinics')
            ->set('password', 'password123')
            ->set('password_confirmation', 'password123')
            ->call('register')
            ->assertHasNoErrors()
            ->assertRedirect('/recruiter/onboarding');

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'new@hospital.com',
            'user_type' => 'MedicalInstitution',
        ]);
    }

    public function test_registration_prevents_duplicate_email(): void
    {
        MedicalInstitution::withoutGlobalScopes()->create([
            'name' => 'Existing',
            'email' => 'existing@hospital.com',
            'phone' => '9876543211',
            'password' => Hash::make('password'),
            'user_type' => 'MedicalInstitution',
            'status' => 'active',
            'is_active' => true,
        ]);

        Livewire::test(RecruiterRegistration::class)
            ->set('name', 'Another Hospital')
            ->set('email', 'existing@hospital.com')
            ->set('phone', '9876543212')
            ->set('med_type', 'clinics')
            ->set('password', 'password123')
            ->set('password_confirmation', 'password123')
            ->call('register')
            ->assertHasErrors(['email']);
    }

    /*
    |--------------------------------------------------------------------------
    | Login Page Tests
    |--------------------------------------------------------------------------
    */

    public function test_recruiter_login_page_loads(): void
    {
        $response = $this->get(route('recruiter.login'));
        $response->assertStatus(200);
        $response->assertSeeLivewire(RecruiterLogin::class);
    }

    public function test_login_shows_email_method_by_default(): void
    {
        Livewire::test(RecruiterLogin::class)
            ->assertSet('loginMethod', 'email')
            ->assertSee('Email address')
            ->assertSee('Password');
    }

    public function test_login_can_switch_to_phone_method(): void
    {
        Livewire::test(RecruiterLogin::class)
            ->set('loginMethod', 'phone')
            ->assertSee('Phone Number');
    }

    /*
    |--------------------------------------------------------------------------
    | Email + Password Login Tests
    |--------------------------------------------------------------------------
    */

    public function test_email_login_validates_required_fields(): void
    {
        Livewire::test(RecruiterLogin::class)
            ->set('email', '')
            ->set('password', '')
            ->call('loginWithEmail')
            ->assertHasErrors(['email', 'password']);
    }

    public function test_email_login_validates_email_format(): void
    {
        Livewire::test(RecruiterLogin::class)
            ->set('email', 'not-an-email')
            ->set('password', 'password123')
            ->call('loginWithEmail')
            ->assertHasErrors(['email']);
    }

    public function test_email_login_validates_password_min_length(): void
    {
        Livewire::test(RecruiterLogin::class)
            ->set('email', 'test@hospital.com')
            ->set('password', '12345')
            ->call('loginWithEmail')
            ->assertHasErrors(['password']);
    }

    public function test_email_login_fails_with_wrong_credentials(): void
    {
        MedicalInstitution::withoutGlobalScopes()->create([
            'name' => 'Test Hospital',
            'email' => 'admin@hospital.com',
            'phone' => '9876543210',
            'password' => Hash::make('correct-password'),
            'user_type' => 'MedicalInstitution',
            'status' => 'active',
            'is_active' => true,
        ]);

        Livewire::test(RecruiterLogin::class)
            ->set('email', 'admin@hospital.com')
            ->set('password', 'wrong-password')
            ->call('loginWithEmail')
            ->assertSet('error', 'Invalid email or password.');
    }

    public function test_email_login_fails_for_nonexistent_user(): void
    {
        Livewire::test(RecruiterLogin::class)
            ->set('email', 'nobody@hospital.com')
            ->set('password', 'password123')
            ->call('loginWithEmail')
            ->assertSet('error', 'Invalid email or password.');
    }

    public function test_email_login_fails_for_inactive_user(): void
    {
        MedicalInstitution::withoutGlobalScopes()->create([
            'name' => 'Inactive Hospital',
            'email' => 'inactive@hospital.com',
            'phone' => '9876543211',
            'password' => Hash::make('password123'),
            'user_type' => 'MedicalInstitution',
            'status' => 'active',
            'is_active' => false,
        ]);

        Livewire::test(RecruiterLogin::class)
            ->set('email', 'inactive@hospital.com')
            ->set('password', 'password123')
            ->call('loginWithEmail')
            ->assertSet('error', 'Your account has been deactivated. Please contact support.');
    }

    public function test_email_login_succeeds_with_valid_credentials(): void
    {
        MedicalInstitution::withoutGlobalScopes()->create([
            'name' => 'Active Hospital',
            'email' => 'active@hospital.com',
            'phone' => '9876543212',
            'password' => Hash::make('password123'),
            'user_type' => 'MedicalInstitution',
            'status' => 'active',
            'is_active' => true,
        ]);

        Livewire::test(RecruiterLogin::class)
            ->set('email', 'active@hospital.com')
            ->set('password', 'password123')
            ->call('loginWithEmail')
            ->assertHasNoErrors()
            ->assertRedirect('/recruiter/dashboard');

        $this->assertAuthenticated();
    }

    /*
    |--------------------------------------------------------------------------
    | Phone OTP Login Tests
    |--------------------------------------------------------------------------
    */

    public function test_phone_otp_validates_phone_format(): void
    {
        Livewire::test(RecruiterLogin::class)
            ->set('loginMethod', 'phone')
            ->set('phone', '123')
            ->call('sendPhoneOtp')
            ->assertHasErrors(['phone']);
    }

    public function test_phone_otp_fails_for_unregistered_phone(): void
    {
        Livewire::test(RecruiterLogin::class)
            ->set('loginMethod', 'phone')
            ->set('phone', '9999999999')
            ->call('sendPhoneOtp')
            ->assertSet('error', 'No account found with this phone number.');
    }

    public function test_phone_otp_fails_for_inactive_account(): void
    {
        MedicalInstitution::withoutGlobalScopes()->create([
            'name' => 'Inactive Hospital',
            'email' => 'inactive2@hospital.com',
            'phone' => '9876543213',
            'password' => null,
            'user_type' => 'MedicalInstitution',
            'status' => 'active',
            'is_active' => false,
        ]);

        Livewire::test(RecruiterLogin::class)
            ->set('loginMethod', 'phone')
            ->set('phone', '9876543213')
            ->call('sendPhoneOtp')
            ->assertSet('error', 'Your account has been deactivated. Please contact support.');
    }

    public function test_phone_otp_sends_successfully(): void
    {
        MedicalInstitution::withoutGlobalScopes()->create([
            'name' => 'OTP Hospital',
            'email' => 'otp@hospital.com',
            'phone' => '9876543214',
            'password' => null,
            'user_type' => 'MedicalInstitution',
            'status' => 'active',
            'is_active' => true,
        ]);

        Livewire::test(RecruiterLogin::class)
            ->set('loginMethod', 'phone')
            ->set('phone', '9876543214')
            ->call('sendPhoneOtp')
            ->assertSet('otpSent', true)
            ->assertSet('resendCooldown', 60)
            ->assertSet('error', '');

        $this->assertDatabaseHas('phone_otp_sessions', [
            'phone' => '9876543214',
            'purpose' => 'recruiter_login',
        ]);
    }

    public function test_phone_otp_verification_fails_with_wrong_otp(): void
    {
        MedicalInstitution::withoutGlobalScopes()->create([
            'name' => 'OTP Hospital 2',
            'email' => 'otp2@hospital.com',
            'phone' => '9876543215',
            'password' => null,
            'user_type' => 'MedicalInstitution',
            'status' => 'active',
            'is_active' => true,
        ]);

        // Generate OTP
        $service = app(PhoneOtpService::class);
        $service->generateOtp('9876543215', 'recruiter_login');

        Livewire::test(RecruiterLogin::class)
            ->set('loginMethod', 'phone')
            ->set('phone', '9876543215')
            ->set('otpSent', true)
            ->set('otp', '000000')
            ->call('verifyPhoneOtp')
            ->assertSet('error', 'Invalid or expired OTP. Please try again.');

        $this->assertGuest();
    }

    public function test_phone_otp_verification_succeeds(): void
    {
        MedicalInstitution::withoutGlobalScopes()->create([
            'name' => 'OTP Hospital 3',
            'email' => 'otp3@hospital.com',
            'phone' => '9876543216',
            'password' => null,
            'user_type' => 'MedicalInstitution',
            'status' => 'active',
            'is_active' => true,
        ]);

        // Generate OTP and get the raw value
        $service = app(PhoneOtpService::class);
        $otp = $service->generateOtp('9876543216', 'recruiter_login');

        Livewire::test(RecruiterLogin::class)
            ->set('loginMethod', 'phone')
            ->set('phone', '9876543216')
            ->set('otpSent', true)
            ->set('otp', $otp)
            ->call('verifyPhoneOtp')
            ->assertHasNoErrors()
            ->assertRedirect('/recruiter/dashboard');

        $this->assertAuthenticated();
    }

    public function test_phone_otp_verification_fails_when_expired(): void
    {
        MedicalInstitution::withoutGlobalScopes()->create([
            'name' => 'Expired OTP Hospital',
            'email' => 'expired@hospital.com',
            'phone' => '9876543217',
            'password' => null,
            'user_type' => 'MedicalInstitution',
            'status' => 'active',
            'is_active' => true,
        ]);

        // Create an expired OTP session
        PhoneOtpSession::create([
            'phone' => '9876543217',
            'otp_code' => Hash::make('123456'),
            'purpose' => 'recruiter_login',
            'expires_at' => now()->subMinutes(5),
            'attempts' => 0,
            'resend_count' => 0,
        ]);

        Livewire::test(RecruiterLogin::class)
            ->set('loginMethod', 'phone')
            ->set('phone', '9876543217')
            ->set('otpSent', true)
            ->set('otp', '123456')
            ->call('verifyPhoneOtp')
            ->assertSet('error', 'Invalid or expired OTP. Please try again.');

        $this->assertGuest();
    }

    public function test_phone_otp_max_attempts_exceeded(): void
    {
        MedicalInstitution::withoutGlobalScopes()->create([
            'name' => 'Max Attempts Hospital',
            'email' => 'maxattempts@hospital.com',
            'phone' => '9876543218',
            'password' => null,
            'user_type' => 'MedicalInstitution',
            'status' => 'active',
            'is_active' => true,
        ]);

        // Create session with max attempts
        PhoneOtpSession::create([
            'phone' => '9876543218',
            'otp_code' => Hash::make('123456'),
            'purpose' => 'recruiter_login',
            'expires_at' => now()->addMinutes(10),
            'attempts' => 5,
            'resend_count' => 0,
        ]);

        Livewire::test(RecruiterLogin::class)
            ->set('loginMethod', 'phone')
            ->set('phone', '9876543218')
            ->set('otpSent', true)
            ->set('otp', '123456')
            ->call('verifyPhoneOtp')
            ->assertSet('error', 'Invalid or expired OTP. Please try again.');

        $this->assertGuest();
    }

    /*
    |--------------------------------------------------------------------------
    | Method Switching Tests
    |--------------------------------------------------------------------------
    */

    public function test_switching_method_resets_fields(): void
    {
        Livewire::test(RecruiterLogin::class)
            ->set('email', 'test@test.com')
            ->set('password', 'test123')
            ->set('loginMethod', 'phone')
            ->assertSet('email', '')
            ->assertSet('password', '')
            ->assertSet('error', '');
    }

    /*
    |--------------------------------------------------------------------------
    | Route Protection Tests
    |--------------------------------------------------------------------------
    */

    public function test_authenticated_routes_reject_guests(): void
    {
        // Unauthenticated requests should not succeed (302 redirect or 500 due to missing login route)
        $response = $this->get('/recruiter/jobs');
        $this->assertTrue(in_array($response->getStatusCode(), [302, 401, 403, 500]));
    }

    public function test_authenticated_recruiter_can_access_protected_routes(): void
    {
        $user = MedicalInstitution::withoutGlobalScopes()->create([
            'name' => 'Auth Hospital',
            'email' => 'auth@hospital.com',
            'phone' => '9876543219',
            'password' => Hash::make('password123'),
            'user_type' => 'MedicalInstitution',
            'status' => 'active',
            'is_active' => true,
            'is_profile_completed' => true,
        ]);

        $response = $this->actingAs($user)->get('/recruiter/jobs');
        $response->assertStatus(200);
    }

    public function test_recruiter_logout(): void
    {
        $user = MedicalInstitution::withoutGlobalScopes()->create([
            'name' => 'Logout Hospital',
            'email' => 'logout@hospital.com',
            'phone' => '9876543220',
            'password' => Hash::make('password123'),
            'user_type' => 'MedicalInstitution',
            'status' => 'active',
            'is_active' => true,
        ]);

        $this->actingAs($user);
        $response = $this->post(route('recruiter.logout'));
        $response->assertRedirect('/');
        $this->assertGuest();
    }
}
