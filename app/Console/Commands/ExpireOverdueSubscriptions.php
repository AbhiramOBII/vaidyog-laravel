<?php

namespace App\Console\Commands;

use App\Services\Subscription\SubscriptionService;
use Illuminate\Console\Command;

class ExpireOverdueSubscriptions extends Command
{
    protected $signature = 'subscription:expire-overdues';
    protected $description = 'Expire overdue subscriptions and featured job purchases.';

    public function handle(SubscriptionService $service): int
    {
        $results = $service->expireOverdueSubscriptions();

        $this->info("Expired {$results['user_subscriptions']} user subs, {$results['recruiter_subscriptions']} recruiter subs, {$results['featured_jobs']} featured jobs.");

        return Command::SUCCESS;
    }
}
