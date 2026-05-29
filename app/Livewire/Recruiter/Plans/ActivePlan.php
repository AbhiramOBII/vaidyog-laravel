<?php

namespace App\Livewire\Recruiter\Plans;

use App\Services\Subscription\SubscriptionService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.recruiter', ['pageTitle' => 'My Plan'])]
class ActivePlan extends Component
{
    public function render()
    {
        $recruiter = auth()->user();
        $subscriptionService = app(SubscriptionService::class);

        $activeSub = $subscriptionService->getActivePlanForRecruiter($recruiter);
        $remaining = $subscriptionService->getRemainingPostingsThisMonth($recruiter);

        return view('livewire.recruiter.plans.active-plan', [
            'subscription' => $activeSub,
            'remaining' => $remaining,
            'featuredPurchases' => $recruiter->featuredJobPurchases ?? collect(),
            'payments' => $recruiter->payments()->latest()->limit(5)->get(),
        ]);
    }
}
