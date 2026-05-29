<?php

namespace App\Livewire\Admin\Plans;

use App\Models\SubscriptionPlan;
use App\Models\SubscriptionPlanOption;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.admin', ['pageTitle' => 'Manage Job Seeker Plan'])]
class JobSeekerPlanForm extends Component
{
    public ?SubscriptionPlan $plan = null;
    public string $name = '';
    public string $slug = '';
    public string $ranking = 'D';
    public array $description = [''];
    public bool $is_active = true;
    public bool $is_recommended = false;
    public int $sort_order = 0;
    public array $options = [];

    public function mount(?SubscriptionPlan $plan = null): void
    {
        if ($plan && $plan->exists) {
            $this->plan = $plan;
            $this->name = $plan->name;
            $this->slug = $plan->slug;
            $this->ranking = $plan->ranking->value;
            $this->description = $plan->description ?? [''];
            $this->is_active = $plan->is_active;
            $this->is_recommended = $plan->is_recommended;
            $this->sort_order = $plan->sort_order;
            $this->options = $plan->options->map(fn($o) => [
                'id' => $o->id,
                'label' => $o->label,
                'duration_type' => $o->duration_type->value,
                'duration_value' => $o->duration_value,
                'price' => (float) $o->price,
                'applications_per_month' => $o->applications_per_month,
                'is_unlimited' => $o->is_unlimited,
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
            'applications_per_month' => null,
            'is_unlimited' => false,
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
            'slug' => 'required|alpha_dash|max:100|unique:subscription_plans,slug,' . ($this->plan?->id ?? ''),
            'ranking' => 'required|in:A,B,C,D',
            'options' => 'array|min:1',
            'options.*.label' => 'required|max:50',
            'options.*.duration_type' => 'required|in:monthly,yearly,lifetime',
            'options.*.duration_value' => 'required|integer|min:1',
            'options.*.price' => 'required|numeric|min:0',
        ]);

        $planData = [
            'name' => $this->name,
            'slug' => $this->slug,
            'ranking' => $this->ranking,
            'description' => array_filter($this->description),
            'is_active' => $this->is_active,
            'is_recommended' => $this->is_recommended,
            'sort_order' => $this->sort_order,
        ];

        if ($this->plan) {
            $this->plan->update($planData);
            $plan = $this->plan;
        } else {
            $plan = SubscriptionPlan::create($planData);
        }

        // Sync options
        $existingIds = [];
        foreach ($this->options as $optData) {
            if (!empty($optData['id'])) {
                $option = SubscriptionPlanOption::find($optData['id']);
                $option?->update([
                    'label' => $optData['label'],
                    'duration_type' => $optData['duration_type'],
                    'duration_value' => $optData['duration_value'],
                    'price' => $optData['price'],
                    'applications_per_month' => $optData['is_unlimited'] ? null : ($optData['applications_per_month'] ?? null),
                    'is_unlimited' => $optData['is_unlimited'],
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
                    'applications_per_month' => $optData['is_unlimited'] ? null : ($optData['applications_per_month'] ?? null),
                    'is_unlimited' => $optData['is_unlimited'],
                    'is_active' => $optData['is_active'],
                    'sort_order' => $optData['sort_order'],
                ]);
                $existingIds[] = $newOpt->id;
            }
        }

        // Remove deleted options
        $plan->options()->whereNotIn('id', $existingIds)->delete();

        session()->flash('success', 'Plan saved successfully.');
        $this->redirect(route('admin.plans.jobseeker.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.plans.job-seeker-plan-form');
    }
}
