<?php

namespace Tests\Feature\Api;

use App\Enums\UserStatusEnum;
use App\Models\JobCategory;
use App\Models\JobPosting;
use App\Models\MedicalInstitution;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RecruiterApiTest extends TestCase
{
    use RefreshDatabase;

    private function makeRecruiter(): MedicalInstitution
    {
        return MedicalInstitution::factory()->create();
    }

    private function authHeader(MedicalInstitution $recruiter): array
    {
        $token = $recruiter->createToken('recruiter-app')->plainTextToken;
        return ['Authorization' => "Bearer {$token}"];
    }

    private function jobPayload(string $categorySlug): array
    {
        return [
            'job_title'            => 'Senior Doctor',
            'employment_type'      => 'full_time',
            'category_slug'        => $categorySlug,
            'subcategory_name'     => 'Cardiology',
            'posting_duration_days'=> 30,
            'job_description'      => str_repeat('This is a detailed job description. ', 5),
            'number_of_vacancies'  => 1,
        ];
    }

    private function makeCategory(): JobCategory
    {
        return JobCategory::create([
            'name'      => 'Doctors',
            'slug'      => 'doctors',
            'is_active' => true,
        ]);
    }

    // ── /me ─────────────────────────────────────────────────────────────

    public function test_me_returns_recruiter_data(): void
    {
        $recruiter = $this->makeRecruiter();

        $this->getJson('/api/recruiter/me', $this->authHeader($recruiter))
             ->assertOk()
             ->assertJsonPath('success', true)
             ->assertJsonPath('data.email', $recruiter->email);
    }

    public function test_me_rejects_unauthenticated(): void
    {
        $this->getJson('/api/recruiter/me')->assertStatus(401);
    }

    // ── Dashboard ────────────────────────────────────────────────────────

    public function test_dashboard_returns_stats(): void
    {
        $recruiter = $this->makeRecruiter();

        $this->getJson('/api/recruiter/dashboard', $this->authHeader($recruiter))
             ->assertOk()
             ->assertJsonPath('success', true)
             ->assertJsonStructure(['data' => ['job_stats', 'application_stats']]);
    }

    // ── Profile ──────────────────────────────────────────────────────────

    public function test_profile_show_returns_data(): void
    {
        $recruiter = $this->makeRecruiter();

        $this->getJson('/api/recruiter/profile', $this->authHeader($recruiter))
             ->assertOk()
             ->assertJsonPath('success', true)
             ->assertJsonPath('data.email', $recruiter->email);
    }

    public function test_profile_update_saves_data(): void
    {
        $recruiter = $this->makeRecruiter();

        $this->putJson('/api/recruiter/profile', [
            'institution_name' => 'City Hospital',
            'med_type'         => 'clinics',
            'city'             => 'Mumbai',
            'state'            => 'Maharashtra',
        ], $this->authHeader($recruiter))
             ->assertOk()
             ->assertJsonPath('success', true);
    }

    // ── Jobs CRUD ────────────────────────────────────────────────────────

    public function test_jobs_list_returns_own_jobs(): void
    {
        $recruiter = $this->makeRecruiter();
        JobPosting::factory()->count(2)->create(['recruiter_id' => $recruiter->id]);

        $this->getJson('/api/recruiter/jobs', $this->authHeader($recruiter))
             ->assertOk()
             ->assertJsonPath('success', true);
    }

    public function test_recruiter_cannot_see_other_recruiter_jobs(): void
    {
        $r1 = $this->makeRecruiter();
        $r2 = $this->makeRecruiter();
        JobPosting::factory()->create(['recruiter_id' => $r1->id, 'job_title' => 'R1 Job']);

        $response = $this->getJson('/api/recruiter/jobs', $this->authHeader($r2))->assertOk();
        $titles = collect($response->json('data.data'))->pluck('job_title');
        $this->assertNotContains('R1 Job', $titles->toArray());
    }

    public function test_can_create_job(): void
    {
        $recruiter = $this->makeRecruiter();
        $category  = $this->makeCategory();

        $this->postJson('/api/recruiter/jobs', $this->jobPayload($category->slug), $this->authHeader($recruiter))
             ->assertStatus(201)
             ->assertJsonPath('success', true)
             ->assertJsonPath('data.job_title', 'Senior Doctor');
    }

    public function test_job_creation_validates_required_fields(): void
    {
        $recruiter = $this->makeRecruiter();

        $this->postJson('/api/recruiter/jobs', [], $this->authHeader($recruiter))
             ->assertStatus(422);
    }

    public function test_can_show_own_job(): void
    {
        $recruiter = $this->makeRecruiter();
        $job       = JobPosting::factory()->create(['recruiter_id' => $recruiter->id]);

        $this->getJson("/api/recruiter/jobs/{$job->slug}", $this->authHeader($recruiter))
             ->assertOk()
             ->assertJsonPath('success', true);
    }

    public function test_cannot_show_other_recruiters_job(): void
    {
        $r1  = $this->makeRecruiter();
        $r2  = $this->makeRecruiter();
        $job = JobPosting::factory()->create(['recruiter_id' => $r1->id]);

        $this->getJson("/api/recruiter/jobs/{$job->slug}", $this->authHeader($r2))
             ->assertStatus(403);
    }

    public function test_can_update_own_job(): void
    {
        $recruiter = $this->makeRecruiter();
        $category  = $this->makeCategory();
        $job       = JobPosting::factory()->create(['recruiter_id' => $recruiter->id]);

        $this->putJson("/api/recruiter/jobs/{$job->slug}",
            $this->jobPayload($category->slug) + ['job_title' => 'Updated Doctor'],
            $this->authHeader($recruiter)
        )->assertOk()->assertJsonPath('success', true);
    }

    public function test_cannot_update_other_recruiters_job(): void
    {
        $r1       = $this->makeRecruiter();
        $r2       = $this->makeRecruiter();
        $category = $this->makeCategory();
        $job      = JobPosting::factory()->create(['recruiter_id' => $r1->id]);

        $this->putJson("/api/recruiter/jobs/{$job->slug}", $this->jobPayload($category->slug), $this->authHeader($r2))
             ->assertStatus(403);
    }

    public function test_can_delete_own_job(): void
    {
        $recruiter = $this->makeRecruiter();
        $job       = JobPosting::factory()->create(['recruiter_id' => $recruiter->id]);

        $this->deleteJson("/api/recruiter/jobs/{$job->slug}", [], $this->authHeader($recruiter))
             ->assertOk()
             ->assertJsonPath('success', true);
    }

    public function test_cannot_delete_other_recruiters_job(): void
    {
        $r1  = $this->makeRecruiter();
        $r2  = $this->makeRecruiter();
        $job = JobPosting::factory()->create(['recruiter_id' => $r1->id]);

        $this->deleteJson("/api/recruiter/jobs/{$job->slug}", [], $this->authHeader($r2))
             ->assertStatus(403);
    }

    // ── Applications ─────────────────────────────────────────────────────

    public function test_applications_list_returns_paginated(): void
    {
        $recruiter = $this->makeRecruiter();

        $this->getJson('/api/recruiter/applications', $this->authHeader($recruiter))
             ->assertOk()
             ->assertJsonPath('success', true);
    }

    public function test_applications_for_job_returns_list(): void
    {
        $recruiter = $this->makeRecruiter();
        $job       = JobPosting::factory()->create(['recruiter_id' => $recruiter->id]);

        $this->getJson("/api/recruiter/jobs/{$job->slug}/applications", $this->authHeader($recruiter))
             ->assertOk()
             ->assertJsonPath('success', true);
    }

    public function test_cannot_view_other_recruiters_job_applications(): void
    {
        $r1  = $this->makeRecruiter();
        $r2  = $this->makeRecruiter();
        $job = JobPosting::factory()->create(['recruiter_id' => $r1->id]);

        $this->getJson("/api/recruiter/jobs/{$job->slug}/applications", $this->authHeader($r2))
             ->assertStatus(403);
    }

    // ── Plans ─────────────────────────────────────────────────────────────

    public function test_plans_list_returns_data(): void
    {
        $recruiter = $this->makeRecruiter();

        $this->getJson('/api/recruiter/plans', $this->authHeader($recruiter))
             ->assertOk()
             ->assertJsonPath('success', true);
    }

    public function test_my_plan_returns_data(): void
    {
        $recruiter = $this->makeRecruiter();

        $this->getJson('/api/recruiter/my-plan', $this->authHeader($recruiter))
             ->assertOk()
             ->assertJsonPath('success', true);
    }
}
