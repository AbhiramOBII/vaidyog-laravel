<?php

namespace Tests\Feature\Profile;

use App\Models\JobSeekerProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileShowTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_show_accessible_to_authenticated_user(): void
    {
        $user = User::factory()->create();
        JobSeekerProfile::create([
            'user_id' => $user->id,
            'first_name' => 'Test',
            'last_name' => 'User',
        ]);

        $this->actingAs($user)
            ->get(route('profile.show'))
            ->assertStatus(200)
            ->assertSee('Test User');
    }

    public function test_profile_show_prompts_creation_when_no_profile(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('profile.show'))
            ->assertStatus(200)
            ->assertSee('Create Your Profile');
    }

    public function test_profile_show_displays_skills(): void
    {
        $user = User::factory()->create();
        JobSeekerProfile::create([
            'user_id' => $user->id,
            'first_name' => 'Dr',
            'last_name' => 'Test',
            'key_skills' => ['Cardiology', 'ECG Reading', 'Emergency Medicine'],
        ]);

        $this->actingAs($user)
            ->get(route('profile.show'))
            ->assertSee('Cardiology')
            ->assertSee('ECG Reading');
    }

    public function test_profile_show_displays_completeness(): void
    {
        $user = User::factory()->create();
        JobSeekerProfile::create([
            'user_id' => $user->id,
            'first_name' => 'Test',
            'last_name' => 'User',
            'profile_completeness' => 40,
        ]);

        $this->actingAs($user)
            ->get(route('profile.show'))
            ->assertOk()
            ->assertSee('Profile Completeness');
    }

    public function test_unauthenticated_user_redirected(): void
    {
        $this->get(route('profile.show'))
            ->assertRedirect();
    }
}
