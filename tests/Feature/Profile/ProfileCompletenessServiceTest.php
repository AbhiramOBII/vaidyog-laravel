<?php

namespace Tests\Feature\Profile;

use App\Models\JobSeekerProfile;
use App\Models\User;
use App\Services\Profile\ProfileCompletenessService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileCompletenessServiceTest extends TestCase
{
    use RefreshDatabase;

    private ProfileCompletenessService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new ProfileCompletenessService();
    }

    public function test_empty_profile_returns_zero(): void
    {
        $user = User::factory()->create();
        $profile = JobSeekerProfile::create(['user_id' => $user->id]);

        $score = $this->service->calculate($profile);
        $this->assertEquals(0, $score);
    }

    public function test_first_and_last_name_gives_4_points(): void
    {
        $user = User::factory()->create();
        $profile = JobSeekerProfile::create([
            'user_id' => $user->id,
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);

        $score = $this->service->calculate($profile);
        $this->assertGreaterThanOrEqual(4, $score);
    }

    public function test_full_personal_info_contributes_to_score(): void
    {
        $user = User::factory()->create();
        $profile = JobSeekerProfile::create([
            'user_id' => $user->id,
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'phone' => '9876543210',
            'date_of_birth' => '1990-05-15',
            'city' => 'Mumbai',
            'state' => 'Maharashtra',
            'nationality' => 'Indian',
            'designation' => 'Doctor',
            'subdesignation' => 'Cardiologist',
            'key_skills' => ['Cardiology', 'ECG', 'Patient Care'],
            'about' => str_repeat('a', 60),
        ]);

        $score = $this->service->calculate($profile);
        $this->assertGreaterThan(20, $score);
    }

    public function test_profile_picture_adds_points(): void
    {
        $user = User::factory()->create();
        $profile = JobSeekerProfile::create([
            'user_id' => $user->id,
            'profile_photo_path' => 'photos/test.jpg',
        ]);

        $score = $this->service->calculate($profile);
        $this->assertEquals(4, $score);
    }

    public function test_resume_adds_points(): void
    {
        $user = User::factory()->create();
        $profile = JobSeekerProfile::create([
            'user_id' => $user->id,
            'resume_path' => 'resumes/test.pdf',
        ]);

        $score = $this->service->calculate($profile);
        $this->assertEquals(4, $score);
    }
}
