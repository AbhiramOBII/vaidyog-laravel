<?php

namespace Tests\Feature\Profile;

use App\Livewire\JobSeeker\Profile\SubModels\CertificationManager;
use App\Livewire\JobSeeker\Profile\SubModels\EducationManager;
use App\Livewire\JobSeeker\Profile\SubModels\EmploymentManager;
use App\Livewire\JobSeeker\Profile\SubModels\LanguageManager;
use App\Livewire\JobSeeker\Profile\SubModels\ProjectManager;
use App\Models\JobSeekerProfile;
use App\Models\User;
use App\Models\UserCertification;
use App\Models\UserEducation;
use App\Models\UserEmployment;
use App\Models\UserLanguage;
use App\Models\UserProject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class SubModelManagersTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        JobSeekerProfile::create(['user_id' => $this->user->id]);
    }

    // Language Manager Tests

    public function test_add_language(): void
    {
        Livewire::actingAs($this->user)
            ->test(LanguageManager::class)
            ->call('openForm')
            ->set('name', 'English')
            ->set('proficiency', 'fluent')
            ->set('can_read', true)
            ->set('can_write', true)
            ->set('can_speak', true)
            ->call('save')
            ->assertSet('showForm', false);

        $this->assertDatabaseHas('user_languages', [
            'user_id' => $this->user->id,
            'name' => 'English',
            'proficiency' => 'fluent',
        ]);
    }

    public function test_language_requires_ability(): void
    {
        Livewire::actingAs($this->user)
            ->test(LanguageManager::class)
            ->call('openForm')
            ->set('name', 'Hindi')
            ->set('can_read', false)
            ->set('can_write', false)
            ->set('can_speak', false)
            ->call('save')
            ->assertHasErrors(['can_read']);
    }

    public function test_edit_language(): void
    {
        $lang = UserLanguage::create([
            'user_id' => $this->user->id,
            'name' => 'Hindi',
            'proficiency' => 'native',
            'can_read' => true,
            'can_write' => true,
            'can_speak' => true,
        ]);

        Livewire::actingAs($this->user)
            ->test(LanguageManager::class)
            ->call('openForm', $lang->id)
            ->assertSet('name', 'Hindi')
            ->set('proficiency', 'fluent')
            ->call('save');

        $this->assertDatabaseHas('user_languages', [
            'id' => $lang->id,
            'proficiency' => 'fluent',
        ]);
    }

    public function test_delete_language(): void
    {
        $lang = UserLanguage::create([
            'user_id' => $this->user->id,
            'name' => 'Tamil',
            'proficiency' => 'beginner',
            'can_speak' => true,
        ]);

        Livewire::actingAs($this->user)
            ->test(LanguageManager::class)
            ->call('delete', $lang->id);

        $this->assertDatabaseMissing('user_languages', ['id' => $lang->id]);
    }

    // Certification Manager Tests

    public function test_add_certification(): void
    {
        Livewire::actingAs($this->user)
            ->test(CertificationManager::class)
            ->call('openForm')
            ->set('name', 'BLS Certification')
            ->set('medical_institute', 'AHA')
            ->set('no_expiry', true)
            ->call('save')
            ->assertSet('showForm', false);

        $this->assertDatabaseHas('user_certifications', [
            'user_id' => $this->user->id,
            'name' => 'BLS Certification',
        ]);
    }

    public function test_certification_requires_name(): void
    {
        Livewire::actingAs($this->user)
            ->test(CertificationManager::class)
            ->call('openForm')
            ->set('name', '')
            ->call('save')
            ->assertHasErrors(['name']);
    }

    // Education Manager Tests

    public function test_add_education(): void
    {
        Livewire::actingAs($this->user)
            ->test(EducationManager::class)
            ->call('openForm')
            ->set('degree', 'MBBS')
            ->set('university', 'AIIMS Delhi')
            ->set('course_type', 'full_time')
            ->set('course_duration_start', 2015)
            ->set('course_duration_end', 2020)
            ->call('save')
            ->assertSet('showForm', false);

        $this->assertDatabaseHas('user_educations', [
            'user_id' => $this->user->id,
            'degree' => 'MBBS',
            'university' => 'AIIMS Delhi',
        ]);
    }

    public function test_education_requires_degree_and_university(): void
    {
        Livewire::actingAs($this->user)
            ->test(EducationManager::class)
            ->call('openForm')
            ->set('degree', '')
            ->set('university', '')
            ->set('course_duration_start', null)
            ->call('save')
            ->assertHasErrors(['degree', 'university', 'course_duration_start']);
    }

    // Employment Manager Tests

    public function test_add_employment(): void
    {
        Livewire::actingAs($this->user)
            ->test(EmploymentManager::class)
            ->call('openForm')
            ->set('company_name', 'Apollo Hospital')
            ->set('job_title', 'Senior Resident')
            ->set('employment_type', 'full_time')
            ->set('joining_date', '2020-01-01')
            ->set('is_current', true)
            ->call('save')
            ->assertSet('showForm', false);

        $this->assertDatabaseHas('user_employments', [
            'user_id' => $this->user->id,
            'company_name' => 'Apollo Hospital',
            'is_current' => true,
        ]);
    }

    public function test_employment_leaving_date_required_when_not_current(): void
    {
        Livewire::actingAs($this->user)
            ->test(EmploymentManager::class)
            ->call('openForm')
            ->set('company_name', 'Max Hospital')
            ->set('job_title', 'Intern')
            ->set('joining_date', '2019-06-01')
            ->set('is_current', false)
            ->set('leaving_date', null)
            ->call('save')
            ->assertHasErrors(['leaving_date']);
    }

    // Project Manager Tests

    public function test_add_project(): void
    {
        Livewire::actingAs($this->user)
            ->test(ProjectManager::class)
            ->call('openForm')
            ->set('title', 'Telemedicine Platform')
            ->set('status', 'completed')
            ->call('save')
            ->assertSet('showForm', false);

        $this->assertDatabaseHas('user_projects', [
            'user_id' => $this->user->id,
            'title' => 'Telemedicine Platform',
        ]);
    }

    public function test_project_requires_title(): void
    {
        Livewire::actingAs($this->user)
            ->test(ProjectManager::class)
            ->call('openForm')
            ->set('title', '')
            ->call('save')
            ->assertHasErrors(['title']);
    }
}
