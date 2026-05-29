<?php

namespace Database\Factories;

use App\Models\JobApplication;
use App\Models\JobPosting;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<JobApplication>
 */
class JobApplicationFactory extends Factory
{
    protected $model = JobApplication::class;

    public function definition(): array
    {
        $job = JobPosting::factory()->create();

        return [
            'job_id' => $job->id,
            'applicant_id' => User::factory(),
            'recruiter_id' => $job->recruiter_id,
            'status' => 'applied',
            'ranking' => 'D',
            'matching_skills' => null,
            'cover_note' => fake()->optional()->sentence(),
            'resume_path' => null,
            'status_dates' => ['applied' => now()->toIso8601String()],
            'applied_at' => now(),
        ];
    }

    public function forJob(JobPosting $job): static
    {
        return $this->state(fn() => [
            'job_id' => $job->id,
            'recruiter_id' => $job->recruiter_id,
        ]);
    }

    public function status(string $status): static
    {
        return $this->state(fn() => [
            'status' => $status,
            'status_dates' => ['applied' => now()->subDays(5)->toIso8601String(), $status => now()->toIso8601String()],
        ]);
    }
}
