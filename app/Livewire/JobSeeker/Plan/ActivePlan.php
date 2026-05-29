<?php

namespace App\Livewire\JobSeeker\Plan;

use App\Services\Subscription\SubscriptionService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app', ['pageTitle' => 'My Plan'])]
class ActivePlan extends Component
{
    public function render()
    {
        $user = auth()->user();
        $subscriptionService = app(SubscriptionService::class);

        $activeSub = $subscriptionService->getActivePlanForJobSeeker($user);
        $remaining = $subscriptionService->getRemainingApplicationsThisMonth($user);

        return view('livewire.job-seeker.plan.active-plan', [
            'subscription' => $activeSub,
            'remaining' => $remaining,
            'payments' => $user->payments()->latest()->limit(5)->get(),
        ]);
    }
}
