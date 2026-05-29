<?php

namespace App\Services\Subscription;

use App\Enums\FeaturedPurchaseStatusEnum;
use App\Enums\SubscriptionStatusEnum;
use App\Models\FeaturedJobPlan;
use App\Models\FeaturedJobPurchase;
use App\Models\JobApplication;
use App\Models\JobPosting;
use App\Models\MedicalInstitution;
use App\Models\Payment;
use App\Models\RecruiterSubscription;
use App\Models\RecruiterSubscriptionPlan;
use App\Models\RecruiterSubscriptionPlanOption;
use App\Models\SubscriptionPlan;
use App\Models\SubscriptionPlanOption;
use App\Models\User;
use App\Models\UserSubscription;
use Illuminate\Support\Carbon;

class SubscriptionService
{
    private ?UserSubscription $cachedJobSeekerPlan = null;
    private ?string $cachedJobSeekerUserId = null;
    private ?RecruiterSubscription $cachedRecruiterPlan = null;
    private ?string $cachedRecruiterId = null;

    public function __construct(
        private PlanSnapshotService $snapshotService
    ) {}

    // ────────────────────────────────────────────────────────────────
    // QUERIES
    // ────────────────────────────────────────────────────────────────

    public function getActivePlanForJobSeeker(User $user): ?UserSubscription
    {
        if ($this->cachedJobSeekerUserId === $user->id && $this->cachedJobSeekerPlan !== null) {
            return $this->cachedJobSeekerPlan;
        }

        $this->cachedJobSeekerUserId = $user->id;
        $this->cachedJobSeekerPlan = UserSubscription::where('user_id', $user->id)
            ->active()
            ->latest('starts_at')
            ->first();

        return $this->cachedJobSeekerPlan;
    }

    public function getActivePlanForRecruiter(User|MedicalInstitution $recruiter): ?RecruiterSubscription
    {
        if ($this->cachedRecruiterId === $recruiter->id && $this->cachedRecruiterPlan !== null) {
            return $this->cachedRecruiterPlan;
        }

        $this->cachedRecruiterId = $recruiter->id;
        $this->cachedRecruiterPlan = RecruiterSubscription::where('recruiter_id', $recruiter->id)
            ->active()
            ->latest('starts_at')
            ->first();

        return $this->cachedRecruiterPlan;
    }

    public function getRankingForJobSeeker(User $user): string
    {
        $plan = $this->getActivePlanForJobSeeker($user);
        return $plan ? ($plan->ranking?->value ?? 'D') : 'D';
    }

    public function getRemainingApplicationsThisMonth(User $user): int|string
    {
        $plan = $this->getActivePlanForJobSeeker($user);

        if (!$plan) {
            // Basic default: 5/month
            $used = $this->getApplicationsThisMonth($user);
            return max(0, 5 - $used);
        }

        if ($plan->is_unlimited) {
            return 'unlimited';
        }

        $used = $this->getApplicationsThisMonth($user);
        return max(0, ($plan->applications_per_month ?? 5) - $used);
    }

    public function canApply(User $user): bool
    {
        $remaining = $this->getRemainingApplicationsThisMonth($user);
        return $remaining === 'unlimited' || $remaining > 0;
    }

    public function getRemainingPostingsThisMonth(User|MedicalInstitution $recruiter): int|string
    {
        $plan = $this->getActivePlanForRecruiter($recruiter);

        if (!$plan || $plan->is_unlimited_postings) {
            return 'unlimited';
        }

        $used = JobPosting::where('recruiter_id', $recruiter->id)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->whereNull('deleted_at')
            ->count();

        return max(0, ($plan->job_postings_per_month ?? 0) - $used);
    }

    public function canPostJob(User|MedicalInstitution $recruiter): bool
    {
        $remaining = $this->getRemainingPostingsThisMonth($recruiter);
        return $remaining === 'unlimited' || $remaining > 0;
    }

    // ────────────────────────────────────────────────────────────────
    // ASSIGNMENT
    // ────────────────────────────────────────────────────────────────

    public function assignPlanToJobSeeker(
        User $user,
        SubscriptionPlanOption $option,
        ?Payment $payment = null,
        bool $byAdmin = false
    ): UserSubscription {
        // Expire existing active subscription
        UserSubscription::where('user_id', $user->id)
            ->where('status', 'active')
            ->update(['status' => SubscriptionStatusEnum::Cancelled->value]);

        $plan = $option->plan;
        $expiresAt = $this->calculateExpiresAt($option->duration_type->value, $option->duration_value);

        $subscription = UserSubscription::create([
            'user_id' => $user->id,
            'subscription_plan_id' => $plan->id,
            'subscription_plan_option_id' => $option->id,
            'plan_name' => $plan->name,
            'ranking' => $plan->ranking->value,
            'applications_per_month' => $option->applications_per_month,
            'is_unlimited' => $option->is_unlimited,
            'status' => SubscriptionStatusEnum::Active->value,
            'starts_at' => now(),
            'expires_at' => $expiresAt,
            'payment_id' => $payment?->id,
            'assigned_by_admin' => $byAdmin,
        ]);

        // Clear cache
        $this->cachedJobSeekerPlan = $subscription;
        $this->cachedJobSeekerUserId = $user->id;

        // Update profile snapshot
        $this->snapshotService->updateSnapshot($user);

        return $subscription;
    }

    public function assignPlanToRecruiter(
        User|MedicalInstitution $recruiter,
        RecruiterSubscriptionPlanOption $option,
        ?Payment $payment = null,
        bool $byAdmin = false
    ): RecruiterSubscription {
        // Expire existing active subscription
        RecruiterSubscription::where('recruiter_id', $recruiter->id)
            ->where('status', 'active')
            ->update(['status' => SubscriptionStatusEnum::Cancelled->value]);

        $plan = $option->plan;
        $expiresAt = $this->calculateExpiresAt($option->duration_type->value, $option->duration_value);

        $subscription = RecruiterSubscription::create([
            'recruiter_id' => $recruiter->id,
            'recruiter_subscription_plan_id' => $plan->id,
            'recruiter_subscription_plan_option_id' => $option->id,
            'plan_name' => $plan->name,
            'recruiter_type' => $plan->recruiter_type->value ?? $plan->recruiter_type,
            'job_postings_per_month' => $option->job_postings_per_month,
            'is_unlimited_postings' => $option->is_unlimited_postings,
            'status' => SubscriptionStatusEnum::Active->value,
            'starts_at' => now(),
            'expires_at' => $expiresAt,
            'payment_id' => $payment?->id,
            'assigned_by_admin' => $byAdmin,
        ]);

        // Clear cache
        $this->cachedRecruiterPlan = $subscription;
        $this->cachedRecruiterId = $recruiter->id;

        // Update profile snapshot
        $this->snapshotService->updateRecruiterSnapshot($recruiter);

        return $subscription;
    }

    public function assignDefaultPlanOnRegistration(User $user): void
    {
        if ($user->user_type === 'user') {
            $plan = SubscriptionPlan::where('slug', 'basic')->first();
            if ($plan) {
                $option = $plan->options()->where('price', 0)->first();
                if ($option) {
                    $this->assignPlanToJobSeeker($user, $option);
                }
            }
        } elseif ($user->user_type === 'MedicalInstitution') {
            $profile = $user->profile ?? $user->profile()->first();
            $medType = $profile?->med_type?->value ?? 'clinics';

            $plan = RecruiterSubscriptionPlan::where('recruiter_type', $medType)->first();
            if ($plan) {
                $option = $plan->options()->orderBy('price')->first();
                if ($option) {
                    $this->assignPlanToRecruiter($user, $option);
                }
            }
        }
    }

    // ────────────────────────────────────────────────────────────────
    // FEATURED JOBS
    // ────────────────────────────────────────────────────────────────

    public function featureJob(
        User|MedicalInstitution $recruiter,
        JobPosting $job,
        FeaturedJobPlan $plan,
        Payment $payment
    ): FeaturedJobPurchase {
        $purchase = FeaturedJobPurchase::create([
            'recruiter_id' => $recruiter->id,
            'job_id' => $job->id,
            'featured_job_plan_id' => $plan->id,
            'price_paid' => $plan->price_per_post,
            'payment_id' => $payment->id,
            'featured_from' => now(),
            'featured_until' => now()->addDays($plan->featured_duration_days),
            'status' => FeaturedPurchaseStatusEnum::Active->value,
        ]);

        $job->update([
            'is_featured' => true,
            'featured_at' => now(),
        ]);

        return $purchase;
    }

    // ────────────────────────────────────────────────────────────────
    // EXPIRY (called by scheduled command)
    // ────────────────────────────────────────────────────────────────

    public function expireOverdueSubscriptions(): array
    {
        // 1. User subscriptions
        $expiredUserSubs = UserSubscription::where('status', 'active')
            ->whereNotNull('expires_at')
            ->where('expires_at', '<=', now())
            ->get();

        foreach ($expiredUserSubs as $sub) {
            $sub->update(['status' => SubscriptionStatusEnum::Expired->value]);
            $user = User::find($sub->user_id);
            if ($user) {
                $this->snapshotService->updateSnapshot($user);
            }
        }

        // 2. Recruiter subscriptions
        $expiredRecSubs = RecruiterSubscription::where('status', 'active')
            ->whereNotNull('expires_at')
            ->where('expires_at', '<=', now())
            ->get();

        foreach ($expiredRecSubs as $sub) {
            $sub->update(['status' => SubscriptionStatusEnum::Expired->value]);
            $recruiter = MedicalInstitution::withoutGlobalScopes()->find($sub->recruiter_id);
            if ($recruiter) {
                $this->snapshotService->updateRecruiterSnapshot($recruiter);
            }
        }

        // 3. Featured job purchases
        $expiredFeatured = FeaturedJobPurchase::where('status', 'active')
            ->whereNotNull('featured_until')
            ->where('featured_until', '<=', now())
            ->get();

        foreach ($expiredFeatured as $purchase) {
            $purchase->update(['status' => FeaturedPurchaseStatusEnum::Expired->value]);
            JobPosting::where('id', $purchase->job_id)->update(['is_featured' => false]);
        }

        return [
            'user_subscriptions' => $expiredUserSubs->count(),
            'recruiter_subscriptions' => $expiredRecSubs->count(),
            'featured_jobs' => $expiredFeatured->count(),
        ];
    }

    // ────────────────────────────────────────────────────────────────
    // HELPERS
    // ────────────────────────────────────────────────────────────────

    private function calculateExpiresAt(string $durationType, int $durationValue): ?Carbon
    {
        return match ($durationType) {
            'monthly' => now()->addMonths($durationValue),
            'yearly' => now()->addYears($durationValue),
            'lifetime' => null,
            default => now()->addMonths($durationValue),
        };
    }

    private function getApplicationsThisMonth(User $user): int
    {
        return JobApplication::where('applicant_id', $user->id)
            ->whereMonth('applied_at', now()->month)
            ->whereYear('applied_at', now()->year)
            ->count();
    }
}
