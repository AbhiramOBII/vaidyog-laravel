<?php

namespace App\Livewire\Admin\Plans;

use App\Enums\MedTypeEnum;
use App\Models\FeaturedJobPlan;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.admin', ['pageTitle' => 'Featured Job Plans'])]
class FeaturedPlanIndex extends Component
{
    public bool $showModal = false;
    public ?int $editingId = null;
    public ?string $recruiter_type = null;
    public string $name = '';
    public string $price_per_post = '0';
    public int $featured_duration_days = 30;
    public bool $is_active = true;

    public function create(): void
    {
        $this->reset(['editingId', 'recruiter_type', 'name', 'price_per_post', 'featured_duration_days', 'is_active']);
        $this->is_active = true;
        $this->featured_duration_days = 30;
        $this->showModal = true;
    }

    public function edit(int $id): void
    {
        $plan = FeaturedJobPlan::findOrFail($id);
        $this->editingId = $plan->id;
        $this->recruiter_type = $plan->recruiter_type?->value;
        $this->name = $plan->name;
        $this->price_per_post = (string) $plan->price_per_post;
        $this->featured_duration_days = $plan->featured_duration_days;
        $this->is_active = $plan->is_active;
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate([
            'name' => 'required|max:100',
            'price_per_post' => 'required|numeric|min:0',
            'featured_duration_days' => 'required|integer|min:1',
        ]);

        $data = [
            'name' => $this->name,
            'recruiter_type' => $this->recruiter_type ?: null,
            'price_per_post' => $this->price_per_post,
            'featured_duration_days' => $this->featured_duration_days,
            'is_active' => $this->is_active,
        ];

        if ($this->editingId) {
            FeaturedJobPlan::find($this->editingId)->update($data);
        } else {
            FeaturedJobPlan::create($data);
        }

        $this->showModal = false;
    }

    public function delete(int $id): void
    {
        FeaturedJobPlan::find($id)?->delete();
    }

    public function render()
    {
        return view('livewire.admin.plans.featured-plan-index', [
            'plans' => FeaturedJobPlan::orderBy('recruiter_type')->get(),
            'medTypes' => MedTypeEnum::cases(),
        ]);
    }
}
