<?php

namespace App\Services\Application;

use App\Models\User;
use App\Services\Subscription\SubscriptionService;

class RankingService
{
    public function __construct(
        private SubscriptionService $subscriptionService
    ) {}

    /**
     * Calculate the application ranking based on the applicant's active subscription plan.
     * Platinum plan → A, Gold → B, Silver → C, Basic/Free → D
     */
    public function calculateRanking(User $applicant): string
    {
        return $this->subscriptionService->getRankingForJobSeeker($applicant);
    }
}
