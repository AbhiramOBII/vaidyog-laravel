<?php

namespace App\Livewire\Frontend\Plans;

use App\Models\SubscriptionPlan;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.public')]
class PlanIndex extends Component
{
    public function render()
    {
        return view('livewire.frontend.plans.plan-index', [
            'plans' => SubscriptionPlan::with(['options' => fn($q) => $q->where('is_active', true)->orderBy('sort_order')])
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get(),
        ]);
    }
}
