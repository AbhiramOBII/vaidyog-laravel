<?php

namespace App\Livewire\Admin\Subscriptions;

use App\Models\RecruiterSubscription;
use App\Models\RecruiterSubscriptionPlan;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.admin', ['pageTitle' => 'Recruiter Subscriptions'])]
class RecruiterSubscriptions extends Component
{
    use WithPagination;

    public function render()
    {
        $planStats = RecruiterSubscriptionPlan::withCount(['subscriptions as active_count' => function ($q) {
            $q->where('status', 'active')
              ->where(fn($q2) => $q2->where('expires_at', '>', now())->orWhereNull('expires_at'));
        }])->orderBy('sort_order')->get();

        return view('livewire.admin.subscriptions.recruiter-subscriptions', [
            'planStats' => $planStats,
            'subscriptions' => RecruiterSubscription::with(['recruiter', 'plan'])
                ->where('status', 'active')
                ->latest('starts_at')
                ->paginate(20),
        ]);
    }
}
