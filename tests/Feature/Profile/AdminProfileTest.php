<?php

namespace Tests\Feature\Profile;

use App\Livewire\Admin\Users\Profile\ProfileEdit as AdminProfileEdit;
use App\Models\JobSeekerProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AdminProfileTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $jobSeeker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->jobSeeker = User::factory()->create();
        JobSeekerProfile::create([
            'user_id' => $this->jobSeeker->id,
            'first_name' => 'Original',
            'last_name' => 'Name',
        ]);
    }

    public function test_admin_can_view_user_profile(): void
    {
        $this->actingAs($this->admin)
            ->get(route('admin.users.profile.show', $this->jobSeeker->id))
            ->assertStatus(200)
            ->assertSee('Original Name');
    }

    public function test_admin_can_edit_user_profile(): void
    {
        Livewire::actingAs($this->admin)
            ->test(AdminProfileEdit::class, ['user' => $this->jobSeeker->id])
            ->set('first_name', 'Updated')
            ->set('last_name', 'Admin')
            ->call('save')
            ->assertHasNoErrors()
            ->assertDispatched('saved');

        $this->assertDatabaseHas('job_seeker_profiles', [
            'user_id' => $this->jobSeeker->id,
            'first_name' => 'Updated',
            'last_name' => 'Admin',
        ]);
    }

    public function test_admin_can_toggle_open_to_work(): void
    {
        Livewire::actingAs($this->admin)
            ->test(AdminProfileEdit::class, ['user' => $this->jobSeeker->id])
            ->set('first_name', 'Original')
            ->set('last_name', 'Name')
            ->set('is_open_to_work', true)
            ->call('save')
            ->assertHasNoErrors();

        $profile = $this->jobSeeker->jobSeekerProfile->fresh();
        $this->assertTrue($profile->is_open_to_work);
    }

    public function test_admin_edit_validates_required_fields(): void
    {
        Livewire::actingAs($this->admin)
            ->test(AdminProfileEdit::class, ['user' => $this->jobSeeker->id])
            ->set('first_name', '')
            ->set('last_name', '')
            ->call('save')
            ->assertHasErrors(['first_name', 'last_name']);
    }
}
