<?php

namespace App\Livewire\Admin\Plans;

use App\Models\SubscriptionPlan;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.admin', ['pageTitle' => 'Job Seeker Plans'])]
class JobSeekerPlanIndex extends Component
{
    public function toggleActive(int $planId): void
    {
        $plan = SubscriptionPlan::findOrFail($planId);
        $plan->update(['is_active' => !$plan->is_active]);
    }

    public function render()
    {
        return view('livewire.admin.plans.job-seeker-plan-index', [
            'plans' => SubscriptionPlan::with('options')->orderBy('sort_order')->get(),
        ]);
    }
}
