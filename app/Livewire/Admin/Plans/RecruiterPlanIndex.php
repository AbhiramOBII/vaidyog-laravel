<?php

namespace App\Livewire\Admin\Plans;

use App\Enums\MedTypeEnum;
use App\Models\RecruiterSubscriptionPlan;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.admin', ['pageTitle' => 'Recruiter Plans'])]
class RecruiterPlanIndex extends Component
{
    public string $activeTab = 'clinics';

    public function toggleActive(int $planId): void
    {
        $plan = RecruiterSubscriptionPlan::findOrFail($planId);
        $plan->update(['is_active' => !$plan->is_active]);
    }

    public function render()
    {
        return view('livewire.admin.plans.recruiter-plan-index', [
            'plans' => RecruiterSubscriptionPlan::with('options')
                ->where('recruiter_type', $this->activeTab)
                ->orderBy('sort_order')
                ->get(),
            'tabs' => MedTypeEnum::cases(),
        ]);
    }
}
