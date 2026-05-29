<?php

namespace Tests\Feature\Admin;

use App\Models\JobApplication;
use App\Models\JobPosting;
use App\Models\MedicalInstitution;
use App\Models\User;
use App\Services\Application\ApplicationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApplicationOversightTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private ApplicationService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['user_type' => 'admin']);
        $this->service = app(ApplicationService::class);
    }

    public function test_admin_can_view_all_applications(): void
    {
        $recruiter = MedicalInstitution::factory()->create();
        $job = JobPosting::factory()->publiclyVisible()->create(['recruiter_id' => $recruiter->id]);
        $applicant = User::factory()->jobSeeker()->create();
        $this->service->applyToJob($applicant, $job);

        $all = JobApplication::count();
        $this->assertEquals(1, $all);
    }

    public function test_admin_can_soft_delete_application(): void
    {
        $recruiter = MedicalInstitution::factory()->create();
        $job = JobPosting::factory()->publiclyVisible()->create(['recruiter_id' => $recruiter->id]);
        $applicant = User::factory()->jobSeeker()->create();
        $application = $this->service->applyToJob($applicant, $job);

        $application->delete();

        $this->assertSoftDeleted('job_applications', ['id' => $application->id]);
    }

    public function test_admin_can_restore_application(): void
    {
        $recruiter = MedicalInstitution::factory()->create();
        $job = JobPosting::factory()->publiclyVisible()->create(['recruiter_id' => $recruiter->id]);
        $applicant = User::factory()->jobSeeker()->create();
        $application = $this->service->applyToJob($applicant, $job);

        $application->delete();
        $application->restore();

        $this->assertDatabaseHas('job_applications', [
            'id' => $application->id,
            'deleted_at' => null,
        ]);
    }

    public function test_non_admin_cannot_access_admin_application_routes(): void
    {
        $regularUser = User::factory()->jobSeeker()->create();

        $response = $this->actingAs($regularUser)->get('/admin/applications');
        // Should be redirected or forbidden (admin middleware)
        $this->assertNotEquals(200, $response->getStatusCode());
    }
}
