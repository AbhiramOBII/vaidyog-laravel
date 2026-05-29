<?php

namespace Tests\Feature\Recruiter;

use App\Exceptions\InvalidStatusTransitionException;
use App\Models\JobApplication;
use App\Models\JobPosting;
use App\Models\MedicalInstitution;
use App\Models\User;
use App\Services\Application\ApplicationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApplicationManagementTest extends TestCase
{
    use RefreshDatabase;

    private MedicalInstitution $recruiter;
    private JobPosting $job;
    private User $applicant;
    private ApplicationService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->recruiter = MedicalInstitution::factory()->create();
        $this->job = JobPosting::factory()->publiclyVisible()->create(['recruiter_id' => $this->recruiter->id]);
        $this->applicant = User::factory()->jobSeeker()->create();
        $this->service = app(ApplicationService::class);
    }

    public function test_recruiter_can_view_applications_for_own_job(): void
    {
        $this->service->applyToJob($this->applicant, $this->job);

        $apps = JobApplication::forRecruiter($this->recruiter->id)->forJob($this->job->id)->get();
        $this->assertCount(1, $apps);
    }

    public function test_recruiter_cannot_view_other_recruiter_applications(): void
    {
        $other = MedicalInstitution::factory()->create();
        $otherJob = JobPosting::factory()->publiclyVisible()->create(['recruiter_id' => $other->id]);
        $otherApplicant = User::factory()->jobSeeker()->create();
        $this->service->applyToJob($otherApplicant, $otherJob);

        $apps = JobApplication::forRecruiter($this->recruiter->id)->get();
        $this->assertCount(0, $apps);
    }

    public function test_recruiter_can_update_status_from_applied_to_reviewed(): void
    {
        $application = $this->service->applyToJob($this->applicant, $this->job);

        $this->service->updateStatus($application, 'reviewed');

        $application->refresh();
        $this->assertEquals('reviewed', $application->status->value);
    }

    public function test_recruiter_can_reject_application(): void
    {
        $application = $this->service->applyToJob($this->applicant, $this->job);

        $this->service->updateStatus($application, 'rejected');

        $application->refresh();
        $this->assertEquals('rejected', $application->status->value);
    }

    public function test_cannot_move_status_backwards(): void
    {
        $application = $this->service->applyToJob($this->applicant, $this->job);
        $this->service->updateStatus($application, 'reviewed');

        $this->expectException(InvalidStatusTransitionException::class);
        $this->service->updateStatus($application, 'applied');
    }

    public function test_cannot_move_from_terminal_status(): void
    {
        $application = $this->service->applyToJob($this->applicant, $this->job);
        $this->service->updateStatus($application, 'rejected');

        $this->expectException(InvalidStatusTransitionException::class);
        $this->service->updateStatus($application, 'reviewed');
    }

    public function test_status_update_timestamps_correctly(): void
    {
        $application = $this->service->applyToJob($this->applicant, $this->job);

        $this->service->updateStatus($application, 'reviewed');
        $application->refresh();

        $dates = $application->status_dates;
        $this->assertArrayHasKey('applied', $dates);
        $this->assertArrayHasKey('reviewed', $dates);
    }

    public function test_recruiter_can_add_notes(): void
    {
        $application = $this->service->applyToJob($this->applicant, $this->job);

        $this->service->updateStatus($application, 'reviewed', 'Good candidate');
        $application->refresh();

        $this->assertEquals('Good candidate', $application->recruiter_notes);
    }

    public function test_bulk_status_update_applies_to_all(): void
    {
        $applicant2 = User::factory()->jobSeeker()->create();
        $app1 = $this->service->applyToJob($this->applicant, $this->job);
        $app2 = $this->service->applyToJob($applicant2, $this->job);

        foreach ([$app1, $app2] as $app) {
            $this->service->updateStatus($app, 'reviewed');
        }

        $this->assertEquals('reviewed', $app1->fresh()->status->value);
        $this->assertEquals('reviewed', $app2->fresh()->status->value);
    }
}
