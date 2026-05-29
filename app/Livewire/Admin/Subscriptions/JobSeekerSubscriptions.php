<?php

namespace App\Livewire\Admin\Subscriptions;

use App\Models\SubscriptionPlan;
use App\Models\UserSubscription;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.admin', ['pageTitle' => 'Job Seeker Subscriptions'])]
class JobSeekerSubscriptions extends Component
{
    use WithPagination;

    public function render()
    {
        $planStats = SubscriptionPlan::withCount(['subscriptions as active_count' => function ($q) {
            $q->where('status', 'active')
              ->where(fn($q2) => $q2->where('expires_at', '>', now())->orWhereNull('expires_at'));
        }])->orderBy('sort_order')->get();

        return view('livewire.admin.subscriptions.job-seeker-subscriptions', [
            'planStats' => $planStats,
            'subscriptions' => UserSubscription::with(['user', 'plan'])
                ->where('status', 'active')
                ->latest('starts_at')
                ->paginate(20),
        ]);
    }
}
