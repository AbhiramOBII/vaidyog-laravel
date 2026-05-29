<?php

namespace Tests\Unit;

use App\Models\JobSeekerProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JobSeekerProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_full_name(): void
    {
        $user = User::factory()->create();
        $profile = JobSeekerProfile::create([
            'user_id' => $user->id,
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);

        $this->assertEquals('John Doe', $profile->getFullName());
    }

    public function test_get_full_name_falls_back_to_user_name(): void
    {
        $user = User::factory()->create(['name' => 'Fallback Name']);
        $profile = JobSeekerProfile::create(['user_id' => $user->id]);

        $this->assertEquals('Fallback Name', $profile->getFullName());
    }

    public function test_get_total_experience_returns_fresher_when_no_employments(): void
    {
        $user = User::factory()->create();
        $profile = JobSeekerProfile::create(['user_id' => $user->id]);

        $this->assertEquals('Fresher', $profile->getTotalExperience());
    }

    public function test_completeness_color_thresholds(): void
    {
        $user = User::factory()->create();
        $profile = JobSeekerProfile::create(['user_id' => $user->id]);

        $profile->profile_completeness = 90;
        $this->assertEquals('green', $profile->getCompletenessColor());

        $profile->profile_completeness = 65;
        $this->assertEquals('teal', $profile->getCompletenessColor());

        $profile->profile_completeness = 40;
        $this->assertEquals('amber', $profile->getCompletenessColor());

        $profile->profile_completeness = 10;
        $this->assertEquals('red', $profile->getCompletenessColor());
    }

    public function test_completeness_label_thresholds(): void
    {
        $user = User::factory()->create();
        $profile = JobSeekerProfile::create(['user_id' => $user->id]);

        $profile->profile_completeness = 90;
        $this->assertEquals('Profile complete', $profile->getCompletenessLabel());

        $profile->profile_completeness = 65;
        $this->assertEquals('Looking good!', $profile->getCompletenessLabel());

        $profile->profile_completeness = 40;
        $this->assertEquals('Building your profile', $profile->getCompletenessLabel());

        $profile->profile_completeness = 10;
        $this->assertEquals('Just getting started', $profile->getCompletenessLabel());
    }

    public function test_profile_picture_url_returns_avatar_when_no_photo(): void
    {
        $user = User::factory()->create();
        $profile = JobSeekerProfile::create([
            'user_id' => $user->id,
            'first_name' => 'Test',
            'last_name' => 'User',
        ]);

        $url = $profile->getProfilePictureUrl();
        $this->assertStringContainsString('ui-avatars.com', $url);
    }

    public function test_resume_url_returns_null_when_no_resume(): void
    {
        $user = User::factory()->create();
        $profile = JobSeekerProfile::create(['user_id' => $user->id]);

        $this->assertNull($profile->getResumeUrl());
    }

    public function test_key_skills_cast_to_array(): void
    {
        $user = User::factory()->create();
        $profile = JobSeekerProfile::create([
            'user_id' => $user->id,
            'key_skills' => ['PHP', 'Laravel', 'MySQL'],
        ]);

        $fresh = $profile->fresh();
        $this->assertIsArray($fresh->key_skills);
        $this->assertCount(3, $fresh->key_skills);
        $this->assertContains('Laravel', $fresh->key_skills);
    }
}
