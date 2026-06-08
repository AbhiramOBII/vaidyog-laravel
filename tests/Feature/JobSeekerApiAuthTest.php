<?php

namespace Tests\Feature;

use App\Enums\UserStatusEnum;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class JobSeekerApiAuthTest extends TestCase
{
    use RefreshDatabase;

    private function makeUser(array $overrides = []): User
    {
        return User::factory()->create(array_merge([
            'user_type' => 'user',
            'password'  => Hash::make('password123'),
            'status'    => UserStatusEnum::Active,
            'is_active' => true,
        ], $overrides));
    }

    // ── Registration ────────────────────────────────────────────────────

    public function test_jobseeker_can_register(): void
    {
        $response = $this->postJson('/api/jobseeker/register', [
            'name'                  => 'Test User',
            'email'                 => 'test@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(201)
                 ->assertJsonPath('success', true)
                 ->assertJsonStructure(['data' => ['user', 'token']]);
    }

    public function test_register_fails_with_duplicate_email(): void
    {
        $this->makeUser(['email' => 'dup@example.com']);

        $this->postJson('/api/jobseeker/register', [
            'name'                  => 'Another User',
            'email'                 => 'dup@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ])->assertStatus(422);
    }

    // ── Login ────────────────────────────────────────────────────────────

    public function test_jobseeker_can_login_with_valid_credentials(): void
    {
        $user = $this->makeUser(['email' => 'js@example.com']);

        $this->postJson('/api/jobseeker/login', [
            'email'    => 'js@example.com',
            'password' => 'password123',
        ])->assertStatus(200)
          ->assertJsonPath('success', true)
          ->assertJsonStructure(['data' => ['user', 'token']]);
    }

    public function test_login_fails_with_wrong_password(): void
    {
        $this->makeUser(['email' => 'js2@example.com']);

        $this->postJson('/api/jobseeker/login', [
            'email'    => 'js2@example.com',
            'password' => 'wrongpassword',
        ])->assertStatus(401)
          ->assertJsonPath('success', false);
    }

    public function test_login_fails_for_unknown_email(): void
    {
        $this->postJson('/api/jobseeker/login', [
            'email'    => 'nobody@example.com',
            'password' => 'password123',
        ])->assertStatus(401)
          ->assertJsonPath('success', false);
    }

    public function test_blocked_user_cannot_login(): void
    {
        $this->makeUser([
            'email'  => 'blocked@example.com',
            'status' => UserStatusEnum::Blocked,
        ]);

        $this->postJson('/api/jobseeker/login', [
            'email'    => 'blocked@example.com',
            'password' => 'password123',
        ])->assertStatus(403)
          ->assertJsonPath('success', false);
    }

    public function test_recruiter_account_cannot_login_via_jobseeker_endpoint(): void
    {
        User::factory()->create([
            'email'     => 'recruiter@example.com',
            'password'  => Hash::make('password123'),
            'user_type' => 'MedicalInstitution',
            'status'    => UserStatusEnum::Active,
            'is_active' => true,
        ]);

        $this->postJson('/api/jobseeker/login', [
            'email'    => 'recruiter@example.com',
            'password' => 'password123',
        ])->assertStatus(401);
    }

    // ── Logout ───────────────────────────────────────────────────────────

    public function test_jobseeker_can_logout(): void
    {
        $user  = $this->makeUser();
        $token = $user->createToken('jobseeker-app')->plainTextToken;

        $this->withHeader('Authorization', "Bearer {$token}")
             ->postJson('/api/jobseeker/logout')
             ->assertStatus(200)
             ->assertJsonPath('success', true);
    }

    public function test_logout_requires_authentication(): void
    {
        $this->postJson('/api/jobseeker/logout')
             ->assertStatus(401);
    }
}
