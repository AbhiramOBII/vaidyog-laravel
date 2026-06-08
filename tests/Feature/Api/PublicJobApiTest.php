<?php

namespace Tests\Feature\Api;

use App\Models\JobPosting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicJobApiTest extends TestCase
{
    use RefreshDatabase;

    // ── GET /api/jobs ────────────────────────────────────────────────────

    public function test_jobs_list_returns_paginated_results(): void
    {
        JobPosting::factory()->publiclyVisible()->count(3)->create();

        $this->getJson('/api/jobs')
             ->assertOk()
             ->assertJsonPath('success', true)
             ->assertJsonStructure(['data' => ['data', 'total', 'per_page']]);
    }

    public function test_jobs_list_excludes_unapproved_jobs(): void
    {
        JobPosting::factory()->pending()->create(['job_title' => 'Pending Job']);
        JobPosting::factory()->publiclyVisible()->create(['job_title' => 'Live Job']);

        $response = $this->getJson('/api/jobs')->assertOk();

        $titles = collect($response->json('data.data'))->pluck('job_title');
        $this->assertContains('Live Job', $titles->toArray());
        $this->assertNotContains('Pending Job', $titles->toArray());
    }

    public function test_jobs_list_filters_by_search(): void
    {
        JobPosting::factory()->publiclyVisible()->create(['job_title' => 'Senior Cardiologist']);
        JobPosting::factory()->publiclyVisible()->create(['job_title' => 'Nurse Practitioner']);

        $response = $this->getJson('/api/jobs?search=Cardiologist')->assertOk();

        $titles = collect($response->json('data.data'))->pluck('job_title');
        $this->assertContains('Senior Cardiologist', $titles->toArray());
        $this->assertNotContains('Nurse Practitioner', $titles->toArray());
    }

    public function test_jobs_list_filters_by_state(): void
    {
        JobPosting::factory()->publiclyVisible()->create(['location_state' => 'Kerala']);
        JobPosting::factory()->publiclyVisible()->create(['location_state' => 'Tamil Nadu']);

        $response = $this->getJson('/api/jobs?state=Kerala')->assertOk();

        $states = collect($response->json('data.data'))->pluck('location_state');
        foreach ($states as $state) {
            $this->assertEquals('Kerala', $state);
        }
    }

    public function test_jobs_list_filters_remote_jobs(): void
    {
        JobPosting::factory()->publiclyVisible()->create(['is_remote' => true]);
        JobPosting::factory()->publiclyVisible()->create(['is_remote' => false]);

        $response = $this->getJson('/api/jobs?remote=1')->assertOk();

        $remote = collect($response->json('data.data'))->pluck('is_remote');
        foreach ($remote as $val) {
            $this->assertTrue($val);
        }
    }

    // ── GET /api/jobs/{job} ──────────────────────────────────────────────

    public function test_show_approved_job_returns_detail(): void
    {
        $job = JobPosting::factory()->publiclyVisible()->create();

        $this->getJson("/api/jobs/{$job->slug}")
             ->assertOk()
             ->assertJsonPath('success', true)
             ->assertJsonPath('data.slug', $job->slug);
    }

    public function test_show_unapproved_job_returns_404(): void
    {
        $job = JobPosting::factory()->pending()->create();

        $this->getJson("/api/jobs/{$job->slug}")
             ->assertStatus(404)
             ->assertJsonPath('success', false);
    }

    public function test_salary_hidden_when_not_disclosed(): void
    {
        $job = JobPosting::factory()->publiclyVisible()->create([
            'salary_min'       => 500000,
            'salary_max'       => 800000,
            'salary_disclosed' => false,
        ]);

        $response = $this->getJson("/api/jobs/{$job->slug}")->assertOk();
        $this->assertNull($response->json('data.salary_min'));
        $this->assertNull($response->json('data.salary_max'));
    }
}
