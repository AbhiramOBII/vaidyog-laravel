<?php

namespace Tests\Feature;

use App\Livewire\JobSeeker\Profile\ProfileEdit;
use App\Models\Designation;
use App\Models\JobSeekerProfile;
use App\Models\SubDesignation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class JobSeekerProfileEditTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::create([
            'name' => 'Test Seeker',
            'email' => 'seeker@test.com',
            'phone' => '9000000001',
            'password' => Hash::make('Password@123'),
            'user_type' => 'user',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Page Load
    |--------------------------------------------------------------------------
    */

    public function test_profile_edit_page_loads_for_authenticated_user(): void
    {
        $response = $this->actingAs($this->user)->get(route('profile.edit'));
        $response->assertStatus(200);
        $response->assertSeeLivewire(ProfileEdit::class);
    }

    public function test_profile_edit_redirects_guests(): void
    {
        $response = $this->get(route('profile.edit'));
        $response->assertRedirect('/login');
    }

    public function test_profile_edit_loads_within_acceptable_time(): void
    {
        // Seed designations for realistic load
        $designation = Designation::create(['name' => 'Doctor', 'sort_order' => 1, 'is_active' => true]);
        SubDesignation::create(['designation_id' => $designation->id, 'name' => 'General Physician', 'sort_order' => 1, 'is_active' => true]);

        $start = microtime(true);

        $this->actingAs($this->user)->get(route('profile.edit'))->assertStatus(200);

        $elapsed = microtime(true) - $start;
        $this->assertLessThan(2.0, $elapsed, "Profile edit page took {$elapsed}s — should be under 2s");
    }

    /*
    |--------------------------------------------------------------------------
    | Personal Info Form
    |--------------------------------------------------------------------------
    */

    public function test_personal_info_validates_required_fields(): void
    {
        Livewire::actingAs($this->user)
            ->test(ProfileEdit::class)
            ->set('first_name', '')
            ->set('last_name', '')
            ->set('date_of_birth', '')
            ->set('phone', '')
            ->set('city', '')
            ->set('state', '')
            ->set('nationality', '')
            ->call('savePersonalInfo')
            ->assertHasErrors(['first_name', 'last_name', 'date_of_birth', 'phone', 'city', 'state', 'nationality']);
    }

    public function test_personal_info_validates_phone_format(): void
    {
        Livewire::actingAs($this->user)
            ->test(ProfileEdit::class)
            ->set('first_name', 'Rahul')
            ->set('last_name', 'Sharma')
            ->set('date_of_birth', '1990-01-15')
            ->set('phone', '123')
            ->set('city', 'Mumbai')
            ->set('state', 'Maharashtra')
            ->set('nationality', 'Indian')
            ->call('savePersonalInfo')
            ->assertHasErrors(['phone']);
    }

    public function test_personal_info_validates_date_of_birth_must_be_past(): void
    {
        Livewire::actingAs($this->user)
            ->test(ProfileEdit::class)
            ->set('first_name', 'Rahul')
            ->set('last_name', 'Sharma')
            ->set('date_of_birth', now()->addDay()->format('Y-m-d'))
            ->set('phone', '9000000001')
            ->set('city', 'Mumbai')
            ->set('state', 'Maharashtra')
            ->set('nationality', 'Indian')
            ->call('savePersonalInfo')
            ->assertHasErrors(['date_of_birth']);
    }

    public function test_personal_info_saves_successfully(): void
    {
        Livewire::actingAs($this->user)
            ->test(ProfileEdit::class)
            ->set('first_name', 'Rahul')
            ->set('last_name', 'Sharma')
            ->set('date_of_birth', '1990-05-15')
            ->set('gender', 'male')
            ->set('phone', '9000000001')
            ->set('city', 'Mumbai')
            ->set('state', 'Maharashtra')
            ->set('pincode', '400001')
            ->set('nationality', 'Indian')
            ->call('savePersonalInfo')
            ->assertHasNoErrors()
            ->assertSet('personalSaved', true)
            ->assertDispatched('saved-personal');

        $this->assertDatabaseHas('job_seeker_profiles', [
            'user_id' => $this->user->id,
            'first_name' => 'Rahul',
            'last_name' => 'Sharma',
            'city' => 'Mumbai',
            'state' => 'Maharashtra',
            'pincode' => '400001',
        ]);
    }

    public function test_personal_info_creates_profile_if_none_exists(): void
    {
        $this->assertNull($this->user->jobSeekerProfile);

        Livewire::actingAs($this->user)
            ->test(ProfileEdit::class)
            ->set('first_name', 'New')
            ->set('last_name', 'User')
            ->set('date_of_birth', '1995-01-01')
            ->set('phone', '9000000001')
            ->set('city', 'Delhi')
            ->set('state', 'Delhi')
            ->set('nationality', 'Indian')
            ->call('savePersonalInfo')
            ->assertHasNoErrors();

        $this->assertNotNull($this->user->fresh()->jobSeekerProfile);
    }

    public function test_personal_info_updates_existing_profile(): void
    {
        JobSeekerProfile::create([
            'user_id' => $this->user->id,
            'first_name' => 'Old',
            'last_name' => 'Name',
            'category_slug' => 'general',
            'category_name' => 'General',
            'subcategory_name' => 'General',
        ]);

        Livewire::actingAs($this->user)
            ->test(ProfileEdit::class)
            ->set('first_name', 'Updated')
            ->set('last_name', 'Name')
            ->set('date_of_birth', '1990-06-15')
            ->set('phone', '9000000001')
            ->set('city', 'Pune')
            ->set('state', 'Maharashtra')
            ->set('nationality', 'Indian')
            ->call('savePersonalInfo')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('job_seeker_profiles', [
            'user_id' => $this->user->id,
            'first_name' => 'Updated',
            'city' => 'Pune',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Professional Info Form
    |--------------------------------------------------------------------------
    */

    public function test_professional_info_validates_required_fields(): void
    {
        Livewire::actingAs($this->user)
            ->test(ProfileEdit::class)
            ->set('designation', '')
            ->set('subdesignation', '')
            ->set('skills', [])
            ->call('saveProfessionalInfo')
            ->assertHasErrors(['designation', 'subdesignation', 'skills']);
    }

    public function test_professional_info_validates_about_min_length(): void
    {
        Livewire::actingAs($this->user)
            ->test(ProfileEdit::class)
            ->set('designation', 'Doctor')
            ->set('subdesignation', 'General Physician')
            ->set('skills', ['Surgery'])
            ->set('about', 'Too short')
            ->call('saveProfessionalInfo')
            ->assertHasErrors(['about']);
    }

    public function test_professional_info_saves_successfully(): void
    {
        Livewire::actingAs($this->user)
            ->test(ProfileEdit::class)
            ->set('designation', 'Doctor')
            ->set('subdesignation', 'General Physician')
            ->set('experience_years', '3.5')
            ->set('current_employer', 'Apollo Hospital')
            ->set('highest_qualification', 'MBBS')
            ->set('skills', ['Patient Care', 'Diagnosis'])
            ->set('about', str_repeat('This is a detailed professional summary. ', 5))
            ->set('is_open_to_work', true)
            ->call('saveProfessionalInfo')
            ->assertHasNoErrors()
            ->assertSet('professionalSaved', true)
            ->assertDispatched('saved-professional');

        $this->assertDatabaseHas('job_seeker_profiles', [
            'user_id' => $this->user->id,
            'designation' => 'Doctor',
            'subdesignation' => 'General Physician',
            'current_employer' => 'Apollo Hospital',
            'highest_qualification' => 'MBBS',
        ]);
    }

    public function test_experience_years_validates_range(): void
    {
        Livewire::actingAs($this->user)
            ->test(ProfileEdit::class)
            ->set('designation', 'Doctor')
            ->set('subdesignation', 'Surgeon')
            ->set('skills', ['Surgery'])
            ->set('experience_years', '65')
            ->call('saveProfessionalInfo')
            ->assertHasErrors(['experience_years']);
    }

    /*
    |--------------------------------------------------------------------------
    | Skills Management
    |--------------------------------------------------------------------------
    */

    public function test_can_add_skill(): void
    {
        Livewire::actingAs($this->user)
            ->test(ProfileEdit::class)
            ->set('skillInput', 'Cardiology')
            ->call('addSkill')
            ->assertSet('skillInput', '')
            ->assertNotSet('skills', []);
    }

    public function test_cannot_add_duplicate_skill(): void
    {
        Livewire::actingAs($this->user)
            ->test(ProfileEdit::class)
            ->set('skills', ['Cardiology'])
            ->set('skillInput', 'Cardiology')
            ->call('addSkill')
            ->assertCount('skills', 1);
    }

    public function test_cannot_exceed_30_skills(): void
    {
        $skills = array_map(fn($i) => "Skill $i", range(1, 30));

        Livewire::actingAs($this->user)
            ->test(ProfileEdit::class)
            ->set('skills', $skills)
            ->set('skillInput', 'One Too Many')
            ->call('addSkill')
            ->assertCount('skills', 30);
    }

    public function test_can_remove_skill(): void
    {
        Livewire::actingAs($this->user)
            ->test(ProfileEdit::class)
            ->set('skills', ['A', 'B', 'C'])
            ->call('removeSkill', 1)
            ->assertSet('skills', ['A', 'C']);
    }

    /*
    |--------------------------------------------------------------------------
    | Profile Picture Upload
    |--------------------------------------------------------------------------
    */

    public function test_profile_picture_upload_validates_image_type(): void
    {
        Storage::fake('public');

        Livewire::actingAs($this->user)
            ->test(ProfileEdit::class)
            ->set('profilePicture', UploadedFile::fake()->create('doc.pdf', 100))
            ->call('uploadProfilePicture')
            ->assertHasErrors(['profilePicture']);
    }

    public function test_profile_picture_upload_validates_max_size(): void
    {
        Storage::fake('public');

        Livewire::actingAs($this->user)
            ->test(ProfileEdit::class)
            ->set('profilePicture', UploadedFile::fake()->image('huge.jpg')->size(3000))
            ->call('uploadProfilePicture')
            ->assertHasErrors(['profilePicture']);
    }

    public function test_profile_picture_uploads_successfully(): void
    {
        Storage::fake('public');

        Livewire::actingAs($this->user)
            ->test(ProfileEdit::class)
            ->set('profilePicture', UploadedFile::fake()->image('avatar.jpg', 200, 200)->size(500))
            ->call('uploadProfilePicture')
            ->assertHasNoErrors();

        $profile = $this->user->fresh()->jobSeekerProfile;
        $this->assertNotNull($profile->profile_photo_path);
        Storage::disk('public')->assertExists($profile->profile_photo_path);
    }

    public function test_profile_picture_can_be_removed(): void
    {
        Storage::fake('public');
        $path = UploadedFile::fake()->image('old.jpg')->store('profile-pictures', 'public');

        JobSeekerProfile::create([
            'user_id' => $this->user->id,
            'profile_photo_path' => $path,
            'category_slug' => 'general',
            'category_name' => 'General',
            'subcategory_name' => 'General',
        ]);

        Livewire::actingAs($this->user)
            ->test(ProfileEdit::class)
            ->call('removeProfilePicture')
            ->assertSet('currentProfilePicture', null);

        $this->assertNull($this->user->fresh()->jobSeekerProfile->profile_photo_path);
    }

    /*
    |--------------------------------------------------------------------------
    | Resume Upload
    |--------------------------------------------------------------------------
    */

    public function test_resume_upload_validates_pdf_only(): void
    {
        Storage::fake('public');

        Livewire::actingAs($this->user)
            ->test(ProfileEdit::class)
            ->set('resume', UploadedFile::fake()->image('photo.jpg'))
            ->call('uploadResume')
            ->assertHasErrors(['resume']);
    }

    public function test_resume_upload_validates_max_size(): void
    {
        Storage::fake('public');

        Livewire::actingAs($this->user)
            ->test(ProfileEdit::class)
            ->set('resume', UploadedFile::fake()->create('big.pdf', 6000, 'application/pdf'))
            ->call('uploadResume')
            ->assertHasErrors(['resume']);
    }

    public function test_resume_uploads_successfully(): void
    {
        Storage::fake('public');

        Livewire::actingAs($this->user)
            ->test(ProfileEdit::class)
            ->set('resume', UploadedFile::fake()->create('resume.pdf', 500, 'application/pdf'))
            ->call('uploadResume')
            ->assertHasNoErrors();

        $profile = $this->user->fresh()->jobSeekerProfile;
        $this->assertNotNull($profile->resume_path);
        Storage::disk('public')->assertExists($profile->resume_path);
    }

    public function test_resume_can_be_removed(): void
    {
        Storage::fake('public');
        $path = UploadedFile::fake()->create('old.pdf', 100, 'application/pdf')->store('resumes', 'public');

        JobSeekerProfile::create([
            'user_id' => $this->user->id,
            'resume_path' => $path,
            'category_slug' => 'general',
            'category_name' => 'General',
            'subcategory_name' => 'General',
        ]);

        Livewire::actingAs($this->user)
            ->test(ProfileEdit::class)
            ->call('removeResume')
            ->assertSet('currentResume', null);

        $this->assertNull($this->user->fresh()->jobSeekerProfile->resume_path);
    }

    /*
    |--------------------------------------------------------------------------
    | Dropdown Interactions
    |--------------------------------------------------------------------------
    */

    public function test_designation_change_loads_sub_designations(): void
    {
        $designation = Designation::create(['name' => 'Doctor', 'sort_order' => 1, 'is_active' => true]);
        SubDesignation::create(['designation_id' => $designation->id, 'name' => 'Surgeon', 'sort_order' => 1, 'is_active' => true]);
        SubDesignation::create(['designation_id' => $designation->id, 'name' => 'Physician', 'sort_order' => 2, 'is_active' => true]);

        Livewire::actingAs($this->user)
            ->test(ProfileEdit::class)
            ->set('designation', 'Doctor')
            ->assertCount('subDesignations', 2);
    }

    public function test_designation_change_resets_sub_designation(): void
    {
        Designation::create(['name' => 'Doctor', 'sort_order' => 1, 'is_active' => true]);
        Designation::create(['name' => 'Nurse', 'sort_order' => 2, 'is_active' => true]);

        Livewire::actingAs($this->user)
            ->test(ProfileEdit::class)
            ->set('subdesignation', 'Surgeon')
            ->set('designation', 'Nurse')
            ->assertSet('subdesignation', '');
    }
}
