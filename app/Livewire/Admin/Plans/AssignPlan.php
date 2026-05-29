<?php

namespace App\Livewire\Admin\Plans;

use App\Models\AdminActionLog;
use App\Models\RecruiterSubscriptionPlan;
use App\Models\RecruiterSubscriptionPlanOption;
use App\Models\SubscriptionPlan;
use App\Models\SubscriptionPlanOption;
use App\Models\User;
use App\Services\Subscription\SubscriptionService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.admin', ['pageTitle' => 'Assign Plan'])]
class AssignPlan extends Component
{
    public string $activeTab = 'job_seeker';

    // Job Seeker assignment
    public string $jsUserSearch = '';
    public ?string $jsUserId = null;
    public ?int $jsPlanId = null;
    public ?int $jsOptionId = null;
    public string $jsNote = '';

    // Recruiter assignment
    public string $recUserSearch = '';
    public ?string $recUserId = null;
    public ?int $recPlanId = null;
    public ?int $recOptionId = null;
    public string $recNote = '';

    public string $successMessage = '';

    public function getJobSeekerUsersProperty()
    {
        if (strlen($this->jsUserSearch) < 2) return collect();
        return User::where('user_type', 'user')
            ->where(function ($q) {
                $q->where('name', 'like', "%{$this->jsUserSearch}%")
                  ->orWhere('email', 'like', "%{$this->jsUserSearch}%");
            })
            ->limit(10)->get(['id', 'name', 'email']);
    }

    public function getRecruiterUsersProperty()
    {
        if (strlen($this->recUserSearch) < 2) return collect();
        return User::where('user_type', 'MedicalInstitution')
            ->where(function ($q) {
                $q->where('name', 'like', "%{$this->recUserSearch}%")
                  ->orWhere('email', 'like', "%{$this->recUserSearch}%");
            })
            ->limit(10)->get(['id', 'name', 'email']);
    }

    public function getJsPlanOptionsProperty()
    {
        if (!$this->jsPlanId) return collect();
        return SubscriptionPlanOption::where('subscription_plan_id', $this->jsPlanId)
            ->where('is_active', true)->orderBy('sort_order')->get();
    }

    public function getRecPlanOptionsProperty()
    {
        if (!$this->recPlanId) return collect();
        return RecruiterSubscriptionPlanOption::where('recruiter_subscription_plan_id', $this->recPlanId)
            ->where('is_active', true)->orderBy('sort_order')->get();
    }

    public function assignJobSeeker(): void
    {
        $this->validate([
            'jsUserId' => 'required',
            'jsOptionId' => 'required',
        ]);

        $user = User::findOrFail($this->jsUserId);
        $option = SubscriptionPlanOption::findOrFail($this->jsOptionId);

        app(SubscriptionService::class)->assignPlanToJobSeeker($user, $option, null, true);

        AdminActionLog::create([
            'admin_id' => Auth::guard('admin')->id(),
            'action' => 'plan_assigned_to_job_seeker',
            'target_type' => 'user',
            'target_id' => $user->id,
            'notes' => $this->jsNote ?: "Assigned plan: {$option->plan->name} ({$option->label})",
        ]);

        $this->reset(['jsUserSearch', 'jsUserId', 'jsPlanId', 'jsOptionId', 'jsNote']);
        $this->successMessage = "Plan assigned to {$user->name} successfully.";
    }

    public function assignRecruiter(): void
    {
        $this->validate([
            'recUserId' => 'required',
            'recOptionId' => 'required',
        ]);

        $user = User::findOrFail($this->recUserId);
        $option = RecruiterSubscriptionPlanOption::findOrFail($this->recOptionId);

        app(SubscriptionService::class)->assignPlanToRecruiter($user, $option, null, true);

        AdminActionLog::create([
            'admin_id' => Auth::guard('admin')->id(),
            'action' => 'plan_assigned_to_recruiter',
            'target_type' => 'user',
            'target_id' => $user->id,
            'notes' => $this->recNote ?: "Assigned plan: {$option->plan->name} ({$option->label})",
        ]);

        $this->reset(['recUserSearch', 'recUserId', 'recPlanId', 'recOptionId', 'recNote']);
        $this->successMessage = "Plan assigned to {$user->name} successfully.";
    }

    public function render()
    {
        return view('livewire.admin.plans.assign-plan', [
            'jsPlans' => SubscriptionPlan::where('is_active', true)->orderBy('sort_order')->get(),
            'recPlans' => RecruiterSubscriptionPlan::where('is_active', true)->orderBy('sort_order')->get(),
        ]);
    }
}
