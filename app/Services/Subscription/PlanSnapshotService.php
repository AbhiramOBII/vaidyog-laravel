<?php

namespace App\Services\Subscription;

use App\Models\MedicalInstitution;
use App\Models\RecruiterSubscription;
use App\Models\User;
use App\Models\UserSubscription;

class PlanSnapshotService
{
    public function updateSnapshot(User $user): void
    {
        if ($user->user_type === 'user') {
            $this->updateJobSeekerSnapshot($user);
        }
    }

    public function updateRecruiterSnapshot(MedicalInstitution|User $recruiter): void
    {
        $subscription = RecruiterSubscription::where('recruiter_id', $recruiter->id)
            ->active()
            ->latest('starts_at')
            ->first();

        $snapshot = $subscription ? [
            'plan_name' => $subscription->plan_name,
            'recruiter_type' => $subscription->recruiter_type?->value ?? $subscription->recruiter_type,
            'job_postings_per_month' => $subscription->job_postings_per_month,
            'is_unlimited_postings' => $subscription->is_unlimited_postings,
            'expires_at' => $subscription->expires_at?->toDateString(),
            'option_label' => $subscription->option?->label,
        ] : null;

        $profile = $recruiter->profile ?? $recruiter->profile()->first();
        if ($profile) {
            $profile->update(['active_plan_snapshot' => $snapshot]);
        }
    }

    private function updateJobSeekerSnapshot(User $user): void
    {
        $subscription = UserSubscription::where('user_id', $user->id)
            ->active()
            ->latest('starts_at')
            ->first();

        $snapshot = $subscription ? [
            'plan_name' => $subscription->plan_name,
            'ranking' => $subscription->ranking?->value ?? $subscription->ranking,
            'applications_per_month' => $subscription->applications_per_month,
            'is_unlimited' => $subscription->is_unlimited,
            'expires_at' => $subscription->expires_at?->toDateString(),
            'option_label' => $subscription->option?->label,
        ] : [
            'plan_name' => 'Basic',
            'ranking' => 'D',
            'applications_per_month' => 5,
            'is_unlimited' => false,
            'expires_at' => null,
            'option_label' => 'Free',
        ];

        $profile = $user->jobSeekerProfile;
        if ($profile) {
            $profile->update(['active_plan_snapshot' => $snapshot]);
        }
    }
}
