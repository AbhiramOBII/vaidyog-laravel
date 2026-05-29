<?php

namespace App\Livewire\JobSeeker\Profile\SubModels;

use App\Models\UserEmployment;
use Livewire\Component;

class EmploymentManager extends Component
{
    public bool $showForm = false;
    public ?int $editingId = null;

    public string $company_name = '';
    public string $job_title = '';
    public string $employment_type = 'full_time';
    public bool $is_current = false;
    public ?string $joining_date = null;
    public ?string $leaving_date = null;
    public ?string $current_salary = '';
    public string $salary_currency = 'INR';
    public string $responsibilities = '';

    protected function rules(): array
    {
        $rules = [
            'company_name' => 'required|max:150',
            'job_title' => 'required|max:150',
            'employment_type' => 'required|in:full_time,part_time,contract,internship,freelance',
            'is_current' => 'boolean',
            'joining_date' => 'required|date|before_or_equal:today',
            'current_salary' => 'nullable|numeric|min:0',
            'salary_currency' => 'required|in:INR,USD,GBP,EUR',
            'responsibilities' => 'nullable|max:1000',
        ];

        if (!$this->is_current) {
            $rules['leaving_date'] = 'required|date|after:joining_date';
        }

        return $rules;
    }

    public function openForm(?int $id = null): void
    {
        if ($id) {
            $emp = UserEmployment::where('user_id', auth()->id())->findOrFail($id);
            $this->editingId = $id;
            $this->company_name = $emp->company_name;
            $this->job_title = $emp->job_title;
            $this->employment_type = $emp->employment_type;
            $this->is_current = $emp->is_current;
            $this->joining_date = $emp->joining_date?->format('Y-m-d');
            $this->leaving_date = $emp->leaving_date?->format('Y-m-d');
            $this->current_salary = $emp->current_salary ?? '';
            $this->salary_currency = $emp->salary_currency ?? 'INR';
            $this->responsibilities = $emp->responsibilities ?? '';
        } else {
            $this->resetForm();
        }
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->validate();

        UserEmployment::updateOrCreate(
            ['id' => $this->editingId, 'user_id' => auth()->id()],
            [
                'user_id' => auth()->id(),
                'company_name' => $this->company_name,
                'job_title' => $this->job_title,
                'employment_type' => $this->employment_type,
                'is_current' => $this->is_current,
                'joining_date' => $this->joining_date,
                'leaving_date' => $this->is_current ? null : ($this->leaving_date ?: null),
                'current_salary' => $this->current_salary ?: null,
                'salary_currency' => $this->salary_currency,
                'responsibilities' => $this->responsibilities ?: null,
            ]
        );

        $this->resetForm();
        $this->showForm = false;
    }

    public function delete(int $id): void
    {
        UserEmployment::where('user_id', auth()->id())->where('id', $id)->delete();
    }

    public function cancel(): void
    {
        $this->resetForm();
        $this->showForm = false;
    }

    private function resetForm(): void
    {
        $this->editingId = null;
        $this->company_name = '';
        $this->job_title = '';
        $this->employment_type = 'full_time';
        $this->is_current = false;
        $this->joining_date = null;
        $this->leaving_date = null;
        $this->current_salary = '';
        $this->salary_currency = 'INR';
        $this->responsibilities = '';
        $this->resetValidation();
    }

    public function render()
    {
        $employments = UserEmployment::where('user_id', auth()->id())->latest()->get();
        return view('livewire.job-seeker.profile.sub-models.employment-manager', compact('employments'));
    }
}
