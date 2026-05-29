<?php

namespace App\Observers;

use App\Models\JobApplication;

class JobApplicationObserver
{
    public function created(JobApplication $application): void
    {
        // Optional: update job's cached application count
        // $application->job?->increment('applications_count');
    }

    public function deleted(JobApplication $application): void
    {
        // Optional: decrement cached count on soft delete
        // $application->job?->decrement('applications_count');
    }

    public function restored(JobApplication $application): void
    {
        // Optional: re-increment on restore
        // $application->job?->increment('applications_count');
    }
}
