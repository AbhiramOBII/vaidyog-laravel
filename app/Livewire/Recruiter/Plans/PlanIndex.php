<?php

namespace App\Livewire\Recruiter\Plans;

use App\Models\MedicalInstitution;
use App\Models\RecruiterSubscription;
use App\Models\RecruiterSubscriptionPlan;
use App\Models\RecruiterSubscriptionPlanOption;
use App\Services\Subscription\SubscriptionService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.recruiter', ['pageTitle' => 'Subscription Plans'])]
class PlanIndex extends Component
{
    public function render()
    {
        $recruiter = MedicalInstitution::withoutGlobalScopes()->find(auth()->id());
        $medType = $recruiter->profile?->med_type?->value ?? 'clinics';

        $subscriptionService = app(SubscriptionService::class);
        $activeSub = $subscriptionService->getActivePlanForRecruiter($recruiter);
        $remaining = $subscriptionService->getRemainingPostingsThisMonth($recruiter);

        $plans = RecruiterSubscriptionPlan::with(['options' => fn($q) => $q->where('is_active', true)->orderBy('sort_order')])
            ->where('recruiter_type', $medType)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $payments = RecruiterSubscription::where('recruiter_id', $recruiter->id)
            ->with('payment')
            ->latest()
            ->take(10)
            ->get();

        // Get free plan option for display when no paid subscription
        $freePlanOption = null;
        if (!$activeSub) {
            $freePlanOption = RecruiterSubscriptionPlanOption::whereHas('plan', fn($q) => $q->where('recruiter_type', $medType)->where('is_active', true))
                ->where('price', 0)
                ->where('is_active', true)
                ->first();
        }

        return view('livewire.recruiter.plans.plan-index', [
            'plans' => $plans,
            'activeSub' => $activeSub,
            'remaining' => $remaining,
            'subscriptions' => $payments,
            'freePlanOption' => $freePlanOption,
        ]);
    }
}
