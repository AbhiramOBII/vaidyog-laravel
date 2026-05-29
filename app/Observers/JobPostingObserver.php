<?php

namespace App\Observers;

use App\Models\JobPosting;

class JobPostingObserver
{
    public function created(JobPosting $job): void
    {
        if ($job->admin_approved && !$job->expires_at) {
            $job->updateQuietly([
                'expires_at' => now()->addDays($job->posting_duration_days),
                'approved_at' => $job->approved_at ?? now(),
            ]);
        }
    }

    public function updated(JobPosting $job): void
    {
        if ($job->wasChanged('admin_approved') && $job->admin_approved && !$job->expires_at) {
            $job->updateQuietly([
                'expires_at' => ($job->approved_at ?? now())->addDays($job->posting_duration_days),
            ]);
        }
    }
}
