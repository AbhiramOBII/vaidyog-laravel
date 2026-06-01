<?php

namespace Tests\Feature\Profile;

use App\Livewire\JobSeeker\Profile\ProfileEdit;
use App\Models\Designation;
use App\Models\JobSeekerProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class ProfileEditTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        JobSeekerProfile::create(['user_id' => $this->user->id]);
    }

    public function test_profile_edit_page_is_accessible(): void
    {
        $this->actingAs($this->user)
            ->get(route('profile.edit'))
            ->assertStatus(200);
    }

    public function test_save_personal_info(): void
    {
        Livewire::actingAs($this->user)
            ->test(ProfileEdit::class)
            ->set('first_name', 'Rahul')
            ->set('last_name', 'Sharma')
            ->set('phone', '9876543210')
            ->set('date_of_birth', '1990-01-15')
            ->set('city', 'Delhi')
            ->set('state', 'Delhi')
            ->set('nationality', 'Indian')
            ->call('savePersonalInfo')
            ->assertHasNoErrors()
            ->assertDispatched('saved-personal');

        $this->assertDatabaseHas('job_seeker_profiles', [
            'user_id' => $this->user->id,
            'first_name' => 'Rahul',
            'last_name' => 'Sharma',
            'phone' => '9876543210',
            'city' => 'Delhi',
        ]);
    }

    public function test_personal_info_validation_fails_without_required_fields(): void
    {
        Livewire::actingAs($this->user)
            ->test(ProfileEdit::class)
            ->set('first_name', '')
            ->set('last_name', '')
            ->set('phone', '')
            ->call('savePersonalInfo')
            ->assertHasErrors(['first_name', 'last_name', 'phone']);
    }

    public function test_save_professional_info(): void
    {
        Designation::create(['name' => 'Doctor', 'is_active' => true]);

        Livewire::actingAs($this->user)
            ->test(ProfileEdit::class)
            ->set('designation', 'Doctor')
            ->set('subdesignation', 'General Physician')
            ->set('skills', ['Surgery', 'Patient Care'])
            ->set('about', str_repeat('A healthcare professional with extensive experience. ', 3))
            ->set('is_open_to_work', true)
            ->call('saveProfessionalInfo')
            ->assertHasNoErrors()
            ->assertDispatched('saved-professional');

        $profile = $this->user->jobSeekerProfile->fresh();
        $this->assertEquals('Doctor', $profile->designation);
        $this->assertTrue($profile->is_open_to_work);
        $this->assertCount(2, $profile->key_skills);
    }

    public function test_skills_cannot_exceed_30(): void
    {
        $component = Livewire::actingAs($this->user)->test(ProfileEdit::class);

        for ($i = 0; $i < 30; $i++) {
            $component->set('skillInput', "Skill {$i}")->call('addSkill');
        }

        $component->set('skillInput', 'Skill 31')->call('addSkill');
        $this->assertCount(30, $component->get('skills'));
    }

    public function test_duplicate_skills_not_added(): void
    {
        Livewire::actingAs($this->user)
            ->test(ProfileEdit::class)
            ->set('skillInput', 'Surgery')
            ->call('addSkill')
            ->set('skillInput', 'Surgery')
            ->call('addSkill')
            ->assertSet('skills', ['Surgery']);
    }

    public function test_upload_profile_picture(): void
    {
        Storage::fake('public');

        Livewire::actingAs($this->user)
            ->test(ProfileEdit::class)
            ->set('profilePicture', UploadedFile::fake()->image('photo.jpg', 200, 200))
            ->call('uploadProfilePicture')
            ->assertHasNoErrors();

        $profile = $this->user->jobSeekerProfile->fresh();
        $this->assertNotNull($profile->profile_photo_path);
        Storage::disk('public')->assertExists($profile->profile_photo_path);
    }

    public function test_upload_profile_picture_rejects_large_file(): void
    {
        Storage::fake('public');

        Livewire::actingAs($this->user)
            ->test(ProfileEdit::class)
            ->set('profilePicture', UploadedFile::fake()->image('large.jpg')->size(3000))
            ->call('uploadProfilePicture')
            ->assertHasErrors(['profilePicture']);
    }

    public function test_upload_resume(): void
    {
        Storage::fake('public');

        Livewire::actingAs($this->user)
            ->test(ProfileEdit::class)
            ->set('resume', UploadedFile::fake()->create('resume.pdf', 500, 'application/pdf'))
            ->call('uploadResume')
            ->assertHasNoErrors();

        $profile = $this->user->jobSeekerProfile->fresh();
        $this->assertNotNull($profile->resume_path);
    }

    public function test_upload_resume_rejects_non_pdf(): void
    {
        Storage::fake('public');

        Livewire::actingAs($this->user)
            ->test(ProfileEdit::class)
            ->set('resume', UploadedFile::fake()->create('doc.docx', 500, 'application/msword'))
            ->call('uploadResume')
            ->assertHasErrors(['resume']);
    }
}
