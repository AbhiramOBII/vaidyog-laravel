<?php

namespace Tests\Feature\Profile;

use App\Models\JobSeekerProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecruiterCandidateViewTest extends TestCase
{
    use RefreshDatabase;

    public function test_recruiter_can_view_open_candidate(): void
    {
        $recruiter = User::factory()->create();
        $jobSeeker = User::factory()->create();
        JobSeekerProfile::create([
            'user_id' => $jobSeeker->id,
            'first_name' => 'Candidate',
            'last_name' => 'Test',
            'is_open_to_work' => true,
        ]);

        $this->actingAs($recruiter)
            ->get(route('recruiter.candidates.show', $jobSeeker->id))
            ->assertStatus(200)
            ->assertSee('Candidate Test');
    }

    public function test_recruiter_cannot_view_closed_candidate(): void
    {
        $recruiter = User::factory()->create();
        $jobSeeker = User::factory()->create();
        JobSeekerProfile::create([
            'user_id' => $jobSeeker->id,
            'first_name' => 'Hidden',
            'last_name' => 'User',
            'is_open_to_work' => false,
        ]);

        $this->actingAs($recruiter)
            ->get(route('recruiter.candidates.show', $jobSeeker->id))
            ->assertStatus(403);
    }

    public function test_recruiter_gets_404_for_nonexistent_user(): void
    {
        $recruiter = User::factory()->create();

        $this->actingAs($recruiter)
            ->get(route('recruiter.candidates.show', 99999))
            ->assertStatus(404);
    }
}
