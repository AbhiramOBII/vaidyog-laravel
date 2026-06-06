<?php

namespace Tests\Feature\AI;

use App\Livewire\JobSeeker\AI\ResumeBuilder;
use App\Models\JobSeekerProfile;
use App\Models\SubscriptionPlan;
use App\Models\SubscriptionPlanOption;
use App\Models\User;
use App\Models\UserSubscription;
use App\Services\AIResumeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Mockery;
use Tests\TestCase;

class ResumeBuilderTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'user_type' => 'user',
            'is_active' => true,
        ]);

        JobSeekerProfile::create([
            'user_id' => $this->user->id,
            'first_name' => 'Rahul',
            'last_name' => 'Sharma',
            'salutation' => 'Dr',
            'designation' => 'Doctor / Physician',
            'email' => $this->user->email,
            'phone' => '9000000001',
            'city' => 'Bengaluru',
            'state' => 'Karnataka',
            'key_skills' => ['Patient Care', 'Diagnosis'],
            'experience_years' => 4,
        ]);

        // Give user an active subscription
        $plan = SubscriptionPlan::create([
            'name' => 'Pro', 'slug' => 'pro', 'ranking' => 'A',
            'description' => [], 'is_active' => true, 'sort_order' => 1,
        ]);
        $option = SubscriptionPlanOption::create([
            'subscription_plan_id' => $plan->id, 'label' => 'Monthly',
            'duration_type' => 'monthly', 'duration_value' => 1,
            'price' => 499, 'applications_per_month' => 50, 'is_unlimited' => false,
            'is_active' => true, 'sort_order' => 1,
        ]);
        UserSubscription::create([
            'user_id' => $this->user->id,
            'subscription_plan_id' => $plan->id,
            'subscription_plan_option_id' => $option->id,
            'plan_name' => 'Pro Plan',
            'ranking' => 'A',
            'applications_per_month' => 50,
            'status' => 'active',
            'starts_at' => now()->subDay(),
            'expires_at' => now()->addMonth(),
        ]);
    }

    public function test_resume_builder_page_is_accessible(): void
    {
        $this->actingAs($this->user)
            ->get(route('jobseeker.ai.resume-builder'))
            ->assertOk();
    }

    public function test_resume_builder_requires_authentication(): void
    {
        $this->get(route('jobseeker.ai.resume-builder'))
            ->assertRedirect();
    }

    public function test_non_subscriber_is_redirected_to_plans(): void
    {
        $freeUser = User::factory()->create(['user_type' => 'user', 'is_active' => true]);
        JobSeekerProfile::create(['user_id' => $freeUser->id, 'first_name' => 'Free', 'last_name' => 'User']);

        Livewire::actingAs($freeUser)
            ->test(ResumeBuilder::class)
            ->assertRedirect(route('plans.index'));
    }

    public function test_resume_builder_renders_step_one(): void
    {
        Livewire::actingAs($this->user)
            ->test(ResumeBuilder::class)
            ->assertSet('step', 1)
            ->assertSee('How would you like to provide your information?')
            ->assertSee('Upload Existing Resume')
            ->assertSee('Type / Paste Content');
    }

    public function test_file_upload_validates_required(): void
    {
        Livewire::actingAs($this->user)
            ->test(ResumeBuilder::class)
            ->set('inputMethod', 'file')
            ->call('startProcessing')
            ->assertHasErrors(['resumeFile']);
    }

    public function test_manual_content_validates_required(): void
    {
        Livewire::actingAs($this->user)
            ->test(ResumeBuilder::class)
            ->set('inputMethod', 'manual')
            ->set('manualContent', '')
            ->call('startProcessing')
            ->assertHasErrors(['manualContent']);
    }

    public function test_manual_content_validates_minimum_length(): void
    {
        Livewire::actingAs($this->user)
            ->test(ResumeBuilder::class)
            ->set('inputMethod', 'manual')
            ->set('manualContent', 'Too short')
            ->call('startProcessing')
            ->assertHasErrors(['manualContent']);
    }

    public function test_valid_manual_input_moves_to_step_two(): void
    {
        Livewire::actingAs($this->user)
            ->test(ResumeBuilder::class)
            ->set('inputMethod', 'manual')
            ->set('manualContent', str_repeat('Experienced doctor with 5 years in cardiology. ', 5))
            ->call('startProcessing')
            ->assertSet('step', 2)
            ->assertSet('processing', true);
    }

    public function test_valid_file_upload_moves_to_step_two(): void
    {
        Storage::fake('local');
        $file = UploadedFile::fake()->create('resume.pdf', 100, 'application/pdf');

        Livewire::actingAs($this->user)
            ->test(ResumeBuilder::class)
            ->set('inputMethod', 'file')
            ->set('resumeFile', $file)
            ->call('startProcessing')
            ->assertSet('step', 2)
            ->assertSet('processing', true);
    }

    public function test_file_upload_rejects_invalid_type(): void
    {
        Storage::fake('local');
        $file = UploadedFile::fake()->create('resume.exe', 100, 'application/x-executable');

        Livewire::actingAs($this->user)
            ->test(ResumeBuilder::class)
            ->set('inputMethod', 'file')
            ->set('resumeFile', $file)
            ->assertHasErrors(['resumeFile']);
    }

    public function test_file_upload_rejects_large_file(): void
    {
        Storage::fake('local');
        $file = UploadedFile::fake()->create('resume.pdf', 6000, 'application/pdf');

        Livewire::actingAs($this->user)
            ->test(ResumeBuilder::class)
            ->set('inputMethod', 'file')
            ->set('resumeFile', $file)
            ->assertHasErrors(['resumeFile']);
    }

    public function test_process_resume_calls_ai_service_and_moves_to_step_three(): void
    {
        $mockData = [
            'name' => 'Dr. Rahul Sharma',
            'title' => 'Senior Cardiologist',
            'summary' => 'Experienced cardiologist with 4+ years of clinical experience.',
            'contact' => [
                'email' => 'rahul@example.com',
                'phone' => '9000000001',
                'location' => 'Bengaluru, Karnataka',
            ],
            'experience' => [
                [
                    'title' => 'Senior Resident',
                    'company' => 'Apollo Hospital',
                    'period' => '2022 — Present',
                    'highlights' => ['Managed 50+ patients daily', 'Led cardiac care unit'],
                ],
            ],
            'education' => [
                ['degree' => 'MBBS', 'institution' => 'JIPMER', 'year' => '2018'],
            ],
            'skills' => ['Patient Care', 'Echocardiography', 'Critical Care'],
            'certifications' => ['BLS Certified'],
            'languages' => ['English', 'Hindi'],
        ];

        $mock = Mockery::mock(AIResumeService::class);
        $mock->shouldReceive('buildResume')->once()->andReturn($mockData);
        $this->app->instance(AIResumeService::class, $mock);

        Livewire::actingAs($this->user)
            ->test(ResumeBuilder::class)
            ->set('inputMethod', 'manual')
            ->set('manualContent', str_repeat('Experienced doctor with 5 years in cardiology. ', 5))
            ->set('tone', 'professional')
            ->call('startProcessing')
            ->call('processResume')
            ->assertSet('step', 3)
            ->assertSet('processing', false)
            ->assertSet('resumeData.name', 'Dr. Rahul Sharma')
            ->assertSee('Your resume is ready!')
            ->assertSee('Dr. Rahul Sharma')
            ->assertSee('Senior Cardiologist');
    }

    public function test_process_resume_handles_ai_failure_gracefully(): void
    {
        $mock = Mockery::mock(AIResumeService::class);
        $mock->shouldReceive('buildResume')->once()->andThrow(new \RuntimeException('API error'));
        $this->app->instance(AIResumeService::class, $mock);

        Livewire::actingAs($this->user)
            ->test(ResumeBuilder::class)
            ->set('inputMethod', 'manual')
            ->set('manualContent', str_repeat('Experienced doctor with 5 years in cardiology. ', 5))
            ->call('startProcessing')
            ->call('processResume')
            ->assertSet('step', 1)
            ->assertSet('processing', false)
            ->assertSet('error', 'Something went wrong: API error');
    }

    public function test_download_pdf_returns_stream(): void
    {
        $resumeData = [
            'name' => 'Dr. Rahul Sharma',
            'title' => 'Cardiologist',
            'summary' => 'Experienced cardiologist.',
            'contact' => ['email' => 'test@test.com', 'phone' => '9000000001', 'location' => 'Bengaluru'],
            'experience' => [],
            'education' => [],
            'skills' => ['Cardiology'],
            'certifications' => [],
            'languages' => ['English'],
        ];

        $response = Livewire::actingAs($this->user)
            ->test(ResumeBuilder::class)
            ->set('step', 3)
            ->set('resumeData', $resumeData)
            ->set('includeImage', false)
            ->call('downloadPdf');

        $response->assertFileDownloaded();
    }

    public function test_update_profile_syncs_data(): void
    {
        $resumeData = [
            'name' => 'Dr. Rahul Sharma',
            'title' => 'Senior Cardiologist',
            'summary' => 'Expert cardiologist with strong clinical skills.',
            'contact' => [],
            'experience' => [],
            'education' => [],
            'skills' => ['Cardiology', 'Echocardiography', 'Critical Care', 'Patient Management'],
            'certifications' => [],
            'languages' => [],
        ];

        Livewire::actingAs($this->user)
            ->test(ResumeBuilder::class)
            ->set('step', 3)
            ->set('resumeData', $resumeData)
            ->set('showUpdatePrompt', true)
            ->call('updateProfile')
            ->assertSet('showUpdatePrompt', false);

        $this->user->refresh();
        $profile = $this->user->jobSeekerProfile;

        $this->assertEquals(
            ['Cardiology', 'Echocardiography', 'Critical Care', 'Patient Management'],
            $profile->key_skills
        );
    }

    public function test_start_over_resets_state(): void
    {
        Livewire::actingAs($this->user)
            ->test(ResumeBuilder::class)
            ->set('step', 3)
            ->set('resumeData', ['name' => 'Test'])
            ->call('startOver')
            ->assertSet('step', 1)
            ->assertSet('resumeData', [])
            ->assertSet('error', '');
    }

    public function test_tone_options_are_available(): void
    {
        Livewire::actingAs($this->user)
            ->test(ResumeBuilder::class)
            ->assertSee('Professional')
            ->assertSee('Academic / Research')
            ->assertSeeHtml('Creative & Engaging')
            ->assertSeeHtml('Concise & Impactful');
    }

    public function test_save_resume_stores_pdf_and_updates_profile(): void
    {
        Storage::fake('public');

        $resumeData = [
            'name' => 'Dr. Rahul Sharma',
            'title' => 'Consultant Physician',
            'summary' => 'Experienced physician.',
            'contact' => ['email' => 'test@test.com', 'phone' => '9000000001', 'location' => 'Bengaluru'],
            'experience' => [],
            'education' => [],
            'skills' => ['Patient Care'],
            'certifications' => [],
            'languages' => ['English'],
        ];

        Livewire::actingAs($this->user)
            ->test(ResumeBuilder::class)
            ->set('step', 3)
            ->set('resumeData', $resumeData)
            ->call('saveResume')
            ->assertSet('resumeSaved', true);

        $this->user->refresh();
        $profile = $this->user->jobSeekerProfile;

        $this->assertNotNull($profile->resume_path);
        Storage::disk('public')->assertExists($profile->resume_path);
    }
}
