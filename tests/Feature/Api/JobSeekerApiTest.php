<?php

namespace Tests\Feature\Api;

use App\Enums\ApplicationStatusEnum;
use App\Enums\UserStatusEnum;
use App\Models\JobApplication;
use App\Models\JobPosting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class JobSeekerApiTest extends TestCase
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

    private function authHeader(User $user): array
    {
        $token = $user->createToken('jobseeker-app')->plainTextToken;
        return ['Authorization' => "Bearer {$token}"];
    }

    // ── /me ─────────────────────────────────────────────────────────────

    public function test_me_returns_authenticated_user(): void
    {
        $user = $this->makeUser();

        $this->getJson('/api/jobseeker/me', $this->authHeader($user))
             ->assertOk()
             ->assertJsonPath('success', true)
             ->assertJsonPath('data.email', $user->email);
    }

    public function test_me_rejects_unauthenticated(): void
    {
        $this->getJson('/api/jobseeker/me')->assertStatus(401);
    }

    // ── Dashboard ────────────────────────────────────────────────────────

    public function test_dashboard_returns_stats(): void
    {
        $user = $this->makeUser();

        $this->getJson('/api/jobseeker/dashboard', $this->authHeader($user))
             ->assertOk()
             ->assertJsonPath('success', true)
             ->assertJsonStructure(['data' => ['stats', 'recent_applications']]);
    }

    // ── Profile show / update ────────────────────────────────────────────

    public function test_profile_show_returns_user_data(): void
    {
        $user = $this->makeUser();

        $this->getJson('/api/jobseeker/profile', $this->authHeader($user))
             ->assertOk()
             ->assertJsonPath('success', true)
             ->assertJsonPath('data.user.email', $user->email);
    }

    public function test_profile_update_creates_profile(): void
    {
        $user = $this->makeUser();

        $this->putJson('/api/jobseeker/profile', [
            'first_name' => 'Rahul',
            'last_name'  => 'Sharma',
            'city'       => 'Kochi',
            'state'      => 'Kerala',
        ], $this->authHeader($user))
             ->assertOk()
             ->assertJsonPath('success', true)
             ->assertJsonPath('data.first_name', 'Rahul');
    }

    public function test_profile_update_validates_required_fields(): void
    {
        $user = $this->makeUser();

        $this->putJson('/api/jobseeker/profile', [], $this->authHeader($user))
             ->assertStatus(422);
    }

    // ── Profile sections (education) ────────────────────────────────────

    public function test_education_index_returns_empty_list(): void
    {
        $user = $this->makeUser();

        $this->getJson('/api/jobseeker/profile/education', $this->authHeader($user))
             ->assertOk()
             ->assertJsonPath('success', true);
    }

    public function test_education_store_creates_entry(): void
    {
        $user = $this->makeUser();

        $this->postJson('/api/jobseeker/profile/education', [
            'university' => 'AIIMS Delhi',
            'degree'     => 'MBBS',
        ], $this->authHeader($user))
             ->assertStatus(201)
             ->assertJsonPath('success', true);
    }

    public function test_education_update_modifies_entry(): void
    {
        $user    = $this->makeUser();
        $headers = $this->authHeader($user);

        $createResp = $this->postJson('/api/jobseeker/profile/education', [
            'university' => 'Old College',
            'degree'     => 'BDS',
        ], $headers)->assertStatus(201);

        $id = $createResp->json('data.id');

        $this->putJson("/api/jobseeker/profile/education/{$id}", [
            'university' => 'New College',
            'degree'     => 'BDS',
        ], $headers)
             ->assertOk()
             ->assertJsonPath('data.university', 'New College');
    }

    public function test_education_delete_removes_entry(): void
    {
        $user    = $this->makeUser();
        $headers = $this->authHeader($user);

        $createResp = $this->postJson('/api/jobseeker/profile/education', [
            'university' => 'Test College',
            'degree'     => 'MD',
        ], $headers)->assertStatus(201);

        $id = $createResp->json('data.id');

        $this->deleteJson("/api/jobseeker/profile/education/{$id}", [], $headers)
             ->assertOk()
             ->assertJsonPath('success', true);
    }

    // ── Applications ─────────────────────────────────────────────────────

    public function test_applications_list_returns_paginated(): void
    {
        $user = $this->makeUser();

        $this->getJson('/api/jobseeker/applications', $this->authHeader($user))
             ->assertOk()
             ->assertJsonPath('success', true)
             ->assertJsonStructure(['data' => ['applications', 'stats']]);
    }

    public function test_can_apply_to_approved_job(): void
    {
        $user = $this->makeUser();
        $job  = JobPosting::factory()->publiclyVisible()->create();

        $this->postJson("/api/jobseeker/jobs/{$job->slug}/apply", [
            'cover_note' => 'I am a great fit.',
        ], $this->authHeader($user))
             ->assertStatus(201)
             ->assertJsonPath('success', true);
    }

    public function test_cannot_apply_to_unapproved_job(): void
    {
        $user = $this->makeUser();
        $job  = JobPosting::factory()->pending()->create();

        $this->postJson("/api/jobseeker/jobs/{$job->slug}/apply", [], $this->authHeader($user))
             ->assertStatus(422)
             ->assertJsonPath('success', false);
    }

    public function test_cannot_apply_twice(): void
    {
        $user    = $this->makeUser();
        $job     = JobPosting::factory()->publiclyVisible()->create();
        $headers = $this->authHeader($user);

        $this->postJson("/api/jobseeker/jobs/{$job->slug}/apply", [], $headers)->assertStatus(201);
        $this->postJson("/api/jobseeker/jobs/{$job->slug}/apply", [], $headers)->assertStatus(409);
    }

    public function test_can_withdraw_application(): void
    {
        $user    = $this->makeUser();
        $job     = JobPosting::factory()->publiclyVisible()->create();
        $headers = $this->authHeader($user);

        $applyResp = $this->postJson("/api/jobseeker/jobs/{$job->slug}/apply", [], $headers)->assertStatus(201);
        $appId     = $applyResp->json('data.id');

        $this->deleteJson("/api/jobseeker/applications/{$appId}", [], $headers)
             ->assertOk()
             ->assertJsonPath('success', true);
    }

    public function test_cannot_view_another_users_application(): void
    {
        $user1 = $this->makeUser();
        $user2 = $this->makeUser();
        $job   = JobPosting::factory()->publiclyVisible()->create();

        $application = JobApplication::create([
            'job_id'       => $job->id,
            'applicant_id' => $user1->id,
            'recruiter_id' => $job->recruiter_id,
            'status'       => ApplicationStatusEnum::Applied,
            'applied_at'   => now(),
        ]);

        $this->getJson("/api/jobseeker/applications/{$application->id}", $this->authHeader($user2))
             ->assertStatus(403);
    }

    // ── Saved Jobs ────────────────────────────────────────────────────────

    public function test_saved_jobs_list_returns_paginated(): void
    {
        $user = $this->makeUser();

        $this->getJson('/api/jobseeker/saved-jobs', $this->authHeader($user))
             ->assertOk()
             ->assertJsonPath('success', true);
    }

    public function test_can_save_a_job(): void
    {
        $user = $this->makeUser();
        $job  = JobPosting::factory()->publiclyVisible()->create();

        $this->postJson("/api/jobseeker/saved-jobs/{$job->slug}", [], $this->authHeader($user))
             ->assertStatus(201)
             ->assertJsonPath('success', true);
    }

    public function test_cannot_save_same_job_twice(): void
    {
        $user    = $this->makeUser();
        $job     = JobPosting::factory()->publiclyVisible()->create();
        $headers = $this->authHeader($user);

        $this->postJson("/api/jobseeker/saved-jobs/{$job->slug}", [], $headers)->assertStatus(201);
        $this->postJson("/api/jobseeker/saved-jobs/{$job->slug}", [], $headers)->assertStatus(409);
    }


    public function test_can_unsave_a_job(): void
    {
        $user    = $this->makeUser();
        $job     = JobPosting::factory()->publiclyVisible()->create();
        $headers = $this->authHeader($user);

        $this->postJson("/api/jobseeker/saved-jobs/{$job->slug}", [], $headers)->assertStatus(201);

        $this->deleteJson("/api/jobseeker/saved-jobs/{$job->slug}", [], $headers)
             ->assertOk()
             ->assertJsonPath('success', true);
    }


    public function test_unsave_nonexistent_returns_404(): void
    {
        $user = $this->makeUser();
        $job  = JobPosting::factory()->publiclyVisible()->create();

        $this->deleteJson("/api/jobseeker/saved-jobs/{$job->slug}", [], $this->authHeader($user))
             ->assertStatus(404);
    }

    // ── Plans ────────────────────────────────────────────────────────────

    public function test_plans_list_returns_data(): void
    {
        $user = $this->makeUser();

        $this->getJson('/api/jobseeker/plans', $this->authHeader($user))
             ->assertOk()
             ->assertJsonPath('success', true);
    }

    public function test_my_plan_returns_data(): void
    {
        $user = $this->makeUser();

        $this->getJson('/api/jobseeker/my-plan', $this->authHeader($user))
             ->assertOk()
             ->assertJsonPath('success', true);
    }
}
