<?php

namespace App\Livewire\Admin\Users\Profile;

use App\Models\Designation;
use App\Models\JobSeekerProfile;
use App\Models\SubDesignation;
use App\Models\User;
use App\Traits\RecalculatesProfileCompleteness;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.admin', ['pageTitle' => 'Edit User Profile'])]
class ProfileEdit extends Component
{
    use RecalculatesProfileCompleteness;

    public string $userId;

    // Personal
    public string $first_name = '';
    public string $last_name = '';
    public ?string $date_of_birth = null;
    public string $gender = '';
    public string $phone = '';
    public string $city = '';
    public string $state = '';
    public string $pincode = '';
    public string $nationality = 'Indian';

    // Professional
    public string $designation = '';
    public string $subdesignation = '';
    public array $skills = [];
    public string $skillInput = '';
    public string $about = '';
    public bool $is_open_to_work = false;

    // Dropdown data
    public array $designations = [];
    public array $subDesignations = [];
    public array $indianStates = [];

    public function mount(string $user): void
    {
        $this->userId = $user;
        $user = User::findOrFail($this->userId);
        $profile = $user->jobSeekerProfile;

        $this->designations = Designation::active()->pluck('name')->toArray();
        $this->indianStates = config('indian_states.states', []);

        if ($profile) {
            $this->first_name = $profile->first_name ?? '';
            $this->last_name = $profile->last_name ?? '';
            $this->date_of_birth = $profile->date_of_birth?->format('Y-m-d');
            $this->gender = $profile->gender ?? '';
            $this->phone = $profile->phone ?? '';
            $this->city = $profile->city ?? '';
            $this->state = $profile->state ?? '';
            $this->pincode = $profile->pincode ?? '';
            $this->nationality = $profile->nationality ?? 'Indian';
            $this->designation = $profile->designation ?? '';
            $this->subdesignation = $profile->subdesignation ?? '';
            $this->skills = $profile->key_skills ?? [];
            $this->about = $profile->about ?? '';
            $this->is_open_to_work = $profile->is_open_to_work;
            $this->loadSubDesignations();
        }
    }

    public function updatedDesignation(): void
    {
        $this->subdesignation = '';
        $this->loadSubDesignations();
    }

    private function loadSubDesignations(): void
    {
        if ($this->designation) {
            $designationModel = Designation::where('name', $this->designation)->first();
            $this->subDesignations = $designationModel
                ? SubDesignation::where('designation_id', $designationModel->id)->active()->pluck('name')->toArray()
                : [];
        } else {
            $this->subDesignations = [];
        }
    }

    public function addSkill(): void
    {
        $skill = trim($this->skillInput);
        if ($skill && !in_array($skill, $this->skills) && count($this->skills) < 30) {
            $this->skills[] = $skill;
        }
        $this->skillInput = '';
    }

    public function removeSkill(int $index): void
    {
        unset($this->skills[$index]);
        $this->skills = array_values($this->skills);
    }

    public function save(): void
    {
        $this->validate([
            'first_name' => 'required|max:60',
            'last_name' => 'required|max:60',
            'date_of_birth' => 'nullable|date|before:today',
            'phone' => 'nullable|digits:10',
            'city' => 'nullable|max:60',
            'state' => 'nullable|max:60',
            'pincode' => 'nullable|digits:6',
            'designation' => 'nullable|max:100',
            'subdesignation' => 'nullable|max:100',
            'about' => 'nullable|max:1000',
        ]);

        $user = User::findOrFail($this->userId);
        $profile = $user->jobSeekerProfile ?? new JobSeekerProfile(['user_id' => $user->id]);

        $profile->fill([
            'user_id' => $user->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'date_of_birth' => $this->date_of_birth ?: null,
            'gender' => $this->gender ?: null,
            'phone' => $this->phone ?: null,
            'city' => $this->city ?: null,
            'state' => $this->state ?: null,
            'pincode' => $this->pincode ?: null,
            'nationality' => $this->nationality ?: null,
            'designation' => $this->designation ?: null,
            'subdesignation' => $this->subdesignation ?: null,
            'key_skills' => $this->skills,
            'about' => $this->about ?: null,
            'is_open_to_work' => $this->is_open_to_work,
        ]);
        $profile->save();

        $this->dispatch('saved');
    }

    public function render()
    {
        return view('livewire.admin.users.profile.profile-edit');
    }
}
