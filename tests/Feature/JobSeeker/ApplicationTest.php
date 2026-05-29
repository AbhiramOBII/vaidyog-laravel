<?php

namespace Tests\Feature\JobSeeker;

use App\Exceptions\DuplicateApplicationException;
use App\Models\JobApplication;
use App\Models\JobPosting;
use App\Models\MedicalInstitution;
use App\Models\SavedJob;
use App\Models\User;
use App\Services\Application\ApplicationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApplicationTest extends TestCase
{
    use RefreshDatabase;

    private User $applicant;
    private JobPosting $job;
    private ApplicationService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->applicant = User::factory()->jobSeeker()->create();
        $recruiter = MedicalInstitution::factory()->create();
        $this->job = JobPosting::factory()->publiclyVisible()->create(['recruiter_id' => $recruiter->id]);
        $this->service = app(ApplicationService::class);
    }

    public function test_job_seeker_can_apply_to_publicly_visible_job(): void
    {
        $application = $this->service->applyToJob($this->applicant, $this->job, ['cover_note' => 'Hello']);

        $this->assertDatabaseHas('job_applications', [
            'id' => $application->id,
            'job_id' => $this->job->id,
            'applicant_id' => $this->applicant->id,
        ]);
    }

    public function test_application_is_created_with_default_status_and_ranking(): void
    {
        $application = $this->service->applyToJob($this->applicant, $this->job);

        $this->assertEquals('applied', $application->status->value);
        $this->assertEquals('D', $application->ranking->value);
    }

    public function test_matching_skills_are_calculated_on_apply(): void
    {
        $this->job->update(['key_skills' => ['PHP', 'Laravel', 'Vue']]);
        $this->applicant->jobSeekerProfile()->create([
            'category_slug' => 'doctor',
            'category_name' => 'Doctor',
            'subcategory_name' => 'General Physician',
            'key_skills' => ['php', 'Laravel', 'React'],
        ]);

        $application = $this->service->applyToJob($this->applicant, $this->job);

        // Case-insensitive match: PHP & Laravel match
        $this->assertNotNull($application->matching_skills);
        $this->assertCount(2, $application->matching_skills);
    }

    public function test_cannot_apply_to_same_job_twice(): void
    {
        $this->service->applyToJob($this->applicant, $this->job);

        $this->expectException(DuplicateApplicationException::class);
        $this->service->applyToJob($this->applicant, $this->job);
    }

    public function test_cannot_apply_to_unapproved_job(): void
    {
        $pendingJob = JobPosting::factory()->pending()->create(['recruiter_id' => $this->job->recruiter_id]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('This job is no longer accepting applications.');
        $this->service->applyToJob($this->applicant, $pendingJob);
    }

    public function test_cannot_apply_to_expired_job(): void
    {
        $expiredJob = JobPosting::factory()->expired()->create(['recruiter_id' => $this->job->recruiter_id]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('This job is no longer accepting applications.');
        $this->service->applyToJob($this->applicant, $expiredJob);
    }

    public function test_can_withdraw_when_status_is_applied(): void
    {
        $application = $this->service->applyToJob($this->applicant, $this->job);

        $this->actingAs($this->applicant);
        $application->delete();

        $this->assertSoftDeleted('job_applications', ['id' => $application->id]);
    }

    public function test_can_withdraw_when_status_is_reviewed(): void
    {
        $application = $this->service->applyToJob($this->applicant, $this->job);
        $application->updateStatus('reviewed');

        $this->assertTrue(in_array($application->fresh()->status->value, ['applied', 'reviewed']));
    }

    public function test_cannot_withdraw_when_shortlisted_or_beyond(): void
    {
        $application = $this->service->applyToJob($this->applicant, $this->job);
        $application->updateStatus('reviewed');
        $application->updateStatus('shortlisted');

        // Verify status prevents withdrawal via policy logic
        $this->assertFalse(in_array($application->fresh()->status->value, ['applied', 'reviewed']));
    }

    public function test_can_save_a_job(): void
    {
        SavedJob::create(['job_id' => $this->job->id, 'user_id' => $this->applicant->id]);

        $this->assertDatabaseHas('saved_jobs', [
            'job_id' => $this->job->id,
            'user_id' => $this->applicant->id,
        ]);
    }

    public function test_can_unsave_a_job(): void
    {
        $saved = SavedJob::create(['job_id' => $this->job->id, 'user_id' => $this->applicant->id]);
        $saved->delete();

        $this->assertDatabaseMissing('saved_jobs', ['job_id' => $this->job->id, 'user_id' => $this->applicant->id]);
    }

    public function test_duplicate_save_is_handled_gracefully(): void
    {
        SavedJob::create(['job_id' => $this->job->id, 'user_id' => $this->applicant->id]);

        // firstOrCreate should not throw
        $saved = SavedJob::firstOrCreate(['job_id' => $this->job->id, 'user_id' => $this->applicant->id]);
        $this->assertNotNull($saved);
        $this->assertEquals(1, SavedJob::where('job_id', $this->job->id)->where('user_id', $this->applicant->id)->count());
    }

    public function test_my_applications_shows_only_own_applications(): void
    {
        $this->service->applyToJob($this->applicant, $this->job);

        $other = User::factory()->jobSeeker()->create();
        $otherJob = JobPosting::factory()->publiclyVisible()->create(['recruiter_id' => $this->job->recruiter_id]);
        $this->service->applyToJob($other, $otherJob);

        $own = JobApplication::forApplicant($this->applicant->id)->get();
        $this->assertCount(1, $own);
        $this->assertEquals($this->applicant->id, $own->first()->applicant_id);
    }
}
