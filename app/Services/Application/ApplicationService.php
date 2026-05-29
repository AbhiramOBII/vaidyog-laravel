<?php

namespace App\Services\Application;

use App\Exceptions\ApplyLimitExceededException;
use App\Exceptions\DuplicateApplicationException;
use App\Exceptions\InvalidStatusTransitionException;
use App\Models\AdminActionLog;
use App\Models\JobApplication;
use App\Models\JobPosting;
use App\Models\User;
use App\Services\Subscription\SubscriptionService;
use Illuminate\Support\Facades\Auth;

class ApplicationService
{
    public function __construct(
        protected RankingService $rankingService,
        protected StatusTransitionService $statusTransitionService,
        protected SubscriptionService $subscriptionService,
    ) {}

    /**
     * @throws DuplicateApplicationException
     * @throws \Exception
     */
    public function applyToJob(User $applicant, JobPosting $job, array $data = []): JobApplication
    {
        // Check job is publicly visible
        if (!$job->admin_approved || !$job->is_active || $job->trashed()) {
            throw new \Exception('This job is no longer accepting applications.');
        }

        if ($job->expires_at && $job->expires_at->isPast()) {
            throw new \Exception('This job is no longer accepting applications.');
        }

        // Check for duplicate application
        $existing = JobApplication::withTrashed()
            ->where('job_id', $job->id)
            ->where('applicant_id', $applicant->id)
            ->whereNull('deleted_at')
            ->exists();

        if ($existing) {
            throw new DuplicateApplicationException();
        }

        // Check subscription apply limit
        if (!$this->subscriptionService->canApply($applicant)) {
            throw new ApplyLimitExceededException();
        }

        // Calculate ranking
        $ranking = $this->rankingService->calculateRanking($applicant);

        // Calculate matching skills
        $jobSkills = $job->key_skills ?? [];
        $applicantSkills = $applicant->profile?->key_skills ?? [];
        $matchingSkills = array_values(array_uintersect(
            $jobSkills,
            $applicantSkills,
            fn ($a, $b) => strcasecmp($a, $b)
        ));

        // Snapshot resume path
        $resumePath = $data['resume_path'] ?? $applicant->profile?->resume_path ?? null;

        return JobApplication::create([
            'job_id' => $job->id,
            'applicant_id' => $applicant->id,
            'recruiter_id' => $job->recruiter_id,
            'status' => 'applied',
            'ranking' => $ranking,
            'matching_skills' => !empty($matchingSkills) ? $matchingSkills : null,
            'cover_note' => $data['cover_note'] ?? null,
            'resume_path' => $resumePath,
            'status_dates' => ['applied' => now()->toIso8601String()],
            'applied_at' => now(),
        ]);
    }

    /**
     * @throws InvalidStatusTransitionException
     */
    public function updateStatus(JobApplication $application, string $status, ?string $notes = null): void
    {
        $currentStatus = $application->status->value;

        $this->statusTransitionService->validateTransition($currentStatus, $status);

        $application->updateStatus($status);

        if ($notes !== null) {
            $application->update(['recruiter_notes' => $notes]);
        }

        // Log to admin_action_logs (only if an authenticated user exists)
        $actorId = Auth::guard('admin')->id() ?? Auth::id();
        if ($actorId) {
            AdminActionLog::create([
                'admin_id' => $actorId,
                'action' => 'application_status_updated',
                'target_type' => 'job_application',
                'target_id' => $application->id,
                'notes' => "Status changed from {$currentStatus} to {$status}",
            ]);
        }
    }
}
