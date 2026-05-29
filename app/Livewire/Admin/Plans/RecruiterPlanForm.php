<?php

namespace App\Livewire\Admin\Plans;

use App\Enums\MedTypeEnum;
use App\Models\RecruiterSubscriptionPlan;
use App\Models\RecruiterSubscriptionPlanOption;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.admin', ['pageTitle' => 'Manage Recruiter Plan'])]
class RecruiterPlanForm extends Component
{
    public ?RecruiterSubscriptionPlan $plan = null;
    public string $name = '';
    public string $slug = '';
    public string $recruiter_type = 'clinics';
    public array $description = [''];
    public bool $is_active = true;
    public int $sort_order = 0;
    public array $options = [];

    public function mount(?RecruiterSubscriptionPlan $plan = null): void
    {
        if ($plan && $plan->exists) {
            $this->plan = $plan;
            $this->name = $plan->name;
            $this->slug = $plan->slug;
            $this->recruiter_type = $plan->recruiter_type->value ?? $plan->recruiter_type;
            $this->description = $plan->description ?? [''];
            $this->is_active = $plan->is_active;
            $this->sort_order = $plan->sort_order;
            $this->options = $plan->options->map(fn($o) => [
                'id' => $o->id,
                'label' => $o->label,
                'duration_type' => $o->duration_type->value,
                'duration_value' => $o->duration_value,
                'price' => (float) $o->price,
                'job_postings_per_month' => $o->job_postings_per_month,
                'is_unlimited_postings' => $o->is_unlimited_postings,
                'is_active' => $o->is_active,
                'sort_order' => $o->sort_order,
            ])->toArray();
        }

        if (empty($this->options)) {
            $this->addOption();
        }
    }

    public function updatedName(): void
    {
        if (!$this->plan) {
            $this->slug = Str::slug($this->name);
        }
    }

    public function addOption(): void
    {
        $this->options[] = [
            'id' => null,
            'label' => '',
            'duration_type' => 'monthly',
            'duration_value' => 1,
            'price' => 0,
            'job_postings_per_month' => null,
            'is_unlimited_postings' => false,
            'is_active' => true,
            'sort_order' => count($this->options),
        ];
    }

    public function removeOption(int $index): void
    {
        unset($this->options[$index]);
        $this->options = array_values($this->options);
    }

    public function addDescription(): void
    {
        $this->description[] = '';
    }

    public function removeDescription(int $index): void
    {
        unset($this->description[$index]);
        $this->description = array_values($this->description);
    }

    public function save(): void
    {
        $this->validate([
            'name' => 'required|max:100',
            'slug' => 'required|alpha_dash|max:100|unique:recruiter_subscription_plans,slug,' . ($this->plan?->id ?? ''),
            'recruiter_type' => 'required|in:clinics,small_hospital,larger_hospital,enterprise,enterprise_branch',
            'options' => 'array|min:1',
            'options.*.label' => 'required|max:50',
            'options.*.duration_type' => 'required|in:monthly,yearly',
            'options.*.duration_value' => 'required|integer|min:1',
            'options.*.price' => 'required|numeric|min:0',
        ]);

        $planData = [
            'name' => $this->name,
            'slug' => $this->slug,
            'recruiter_type' => $this->recruiter_type,
            'description' => array_filter($this->description),
            'is_active' => $this->is_active,
            'sort_order' => $this->sort_order,
        ];

        if ($this->plan) {
            $this->plan->update($planData);
            $plan = $this->plan;
        } else {
            $plan = RecruiterSubscriptionPlan::create($planData);
        }

        // Sync options
        $existingIds = [];
        foreach ($this->options as $optData) {
            if (!empty($optData['id'])) {
                $option = RecruiterSubscriptionPlanOption::find($optData['id']);
                $option?->update([
                    'label' => $optData['label'],
                    'duration_type' => $optData['duration_type'],
                    'duration_value' => $optData['duration_value'],
                    'price' => $optData['price'],
                    'job_postings_per_month' => $optData['is_unlimited_postings'] ? null : ($optData['job_postings_per_month'] ?? null),
                    'is_unlimited_postings' => $optData['is_unlimited_postings'],
                    'is_active' => $optData['is_active'],
                    'sort_order' => $optData['sort_order'],
                ]);
                $existingIds[] = $option->id;
            } else {
                $newOpt = $plan->options()->create([
                    'label' => $optData['label'],
                    'duration_type' => $optData['duration_type'],
                    'duration_value' => $optData['duration_value'],
                    'price' => $optData['price'],
                    'job_postings_per_month' => $optData['is_unlimited_postings'] ? null : ($optData['job_postings_per_month'] ?? null),
                    'is_unlimited_postings' => $optData['is_unlimited_postings'],
                    'is_active' => $optData['is_active'],
                    'sort_order' => $optData['sort_order'],
                ]);
                $existingIds[] = $newOpt->id;
            }
        }

        $plan->options()->whereNotIn('id', $existingIds)->delete();

        session()->flash('success', 'Recruiter plan saved successfully.');
        $this->redirect(route('admin.plans.recruiter.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.plans.recruiter-plan-form', [
            'medTypes' => MedTypeEnum::cases(),
        ]);
    }
}
