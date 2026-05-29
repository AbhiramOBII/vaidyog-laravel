<?php

namespace Database\Factories;

use App\Models\JobPosting;
use App\Models\MedicalInstitution;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<JobPosting>
 */
class JobPostingFactory extends Factory
{
    protected $model = JobPosting::class;

    public function definition(): array
    {
        return [
            'recruiter_id' => MedicalInstitution::factory(),
            'job_title' => fake()->jobTitle(),
            'job_description' => fake()->paragraphs(3, true),
            'key_skills' => ['PHP', 'Laravel', 'MySQL'],
            'employment_type' => 'full_time',
            'experience_min' => 1,
            'experience_max' => 5,
            'location_city' => fake()->city(),
            'location_state' => 'Maharashtra',
            'number_of_vacancies' => 2,
            'salary_min' => 300000,
            'salary_max' => 600000,
            'institution_name' => fake()->company(),
            'admin_approved' => true,
            'is_active' => true,
            'posting_duration_days' => 30,
            'expires_at' => now()->addDays(30),
        ];
    }

    public function publiclyVisible(): static
    {
        return $this->state(fn() => [
            'admin_approved' => true,
            'is_active' => true,
            'expires_at' => now()->addDays(30),
        ]);
    }

    public function expired(): static
    {
        return $this->state(fn() => [
            'expires_at' => now()->subDay(),
        ]);
    }

    public function pending(): static
    {
        return $this->state(fn() => [
            'admin_approved' => false,
        ]);
    }
}
