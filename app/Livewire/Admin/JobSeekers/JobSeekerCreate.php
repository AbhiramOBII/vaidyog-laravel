<?php

namespace App\Livewire\Admin\JobSeekers;

use App\Enums\AuthProviderEnum;
use App\Enums\UserStatusEnum;
use App\Models\JobCategory;
use App\Models\JobSeekerProfile;
use App\Models\JobSubcategory;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.admin')]
class JobSeekerCreate extends Component
{
    // Section 1 — Account
    public string $name = '';
    public string $phone = '';
    public string $email = '';
    public string $authMethod = 'phone';
    public string $tempPassword = '';
    public string $accountStatus = 'active';

    // Section 2 — Classification
    public string $categoryId = '';
    public string $subcategoryId = '';

    // Section 3 — Profile basics
    public string $gender = '';
    public string $dateOfBirth = '';
    public string $city = '';
    public string $state = '';
    public string $pincode = '';
    public string $experienceYears = '';
    public string $highestQualification = '';
    public string $currentEmployer = '';

    // Section 4 — Toggles
    public bool $phoneVerified = false;
    public bool $profileCompleted = false;

    public function updatedCategoryId(): void
    {
        $this->subcategoryId = '';
    }

    #[Computed]
    public function categories()
    {
        return JobCategory::where('is_active', true)->orderBy('sort_order')->get(['id', 'slug', 'name']);
    }

    #[Computed]
    public function subcategories()
    {
        if (!$this->categoryId) {
            return collect();
        }

        return JobSubcategory::where('job_category_id', $this->categoryId)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get(['id', 'job_category_id', 'name', 'slug']);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'regex:/^[6-9]\d{9}$/', Rule::unique('users', 'phone')],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('users', 'email')],
            'authMethod' => ['required', Rule::in(array_column(AuthProviderEnum::cases(), 'value'))],
            'tempPassword' => [$this->authMethod === 'email' ? 'nullable' : 'exclude', 'string', 'min:6', 'max:100'],
            'accountStatus' => ['required', Rule::in(array_column(UserStatusEnum::cases(), 'value'))],
            'categoryId' => ['required', 'exists:job_categories,id'],
            'subcategoryId' => [
                'required',
                Rule::exists('job_subcategories', 'id')->where('job_category_id', $this->categoryId),
            ],
            'gender' => ['nullable', 'string', Rule::in(['male', 'female', 'other'])],
            'dateOfBirth' => ['nullable', 'date', 'before:today'],
            'city' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'pincode' => ['nullable', 'string', 'regex:/^\d{6}$/'],
            'experienceYears' => ['nullable', 'numeric', 'min:0', 'max:60'],
            'highestQualification' => ['nullable', 'string', 'max:255'],
            'currentEmployer' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'phone.regex' => 'Enter a valid 10-digit Indian mobile number.',
            'pincode.regex' => 'Enter a valid 6-digit pincode.',
            'subcategoryId.exists' => 'Selected sub-category does not belong to the chosen category.',
        ];
    }

    public function save(): void
    {
        $validated = $this->validate();

        $category = JobCategory::find($this->categoryId);
        $subcategory = JobSubcategory::find($this->subcategoryId);

        DB::transaction(function () use ($category, $subcategory) {
            $user = User::create([
                'name' => $this->name,
                'phone' => $this->phone,
                'email' => $this->email ?: null,
                'password' => ($this->authMethod === 'email' && $this->tempPassword) ? $this->tempPassword : null,
                'user_type' => 'user',
                'status' => $this->accountStatus,
                'auth_provider' => $this->authMethod,
                'is_active' => in_array($this->accountStatus, ['active']),
                'is_profile_completed' => $this->profileCompleted,
                'phone_verified_at' => $this->phoneVerified ? now() : null,
            ]);

            JobSeekerProfile::create([
                'user_id' => $user->id,
                'category_slug' => $category->slug,
                'category_name' => $category->name,
                'subcategory_name' => $subcategory->name,
                'gender' => $this->gender ?: null,
                'date_of_birth' => $this->dateOfBirth ?: null,
                'city' => $this->city ?: null,
                'state' => $this->state ?: null,
                'pincode' => $this->pincode ?: null,
                'experience_years' => $this->experienceYears !== '' ? $this->experienceYears : null,
                'highest_qualification' => $this->highestQualification ?: null,
                'current_employer' => $this->currentEmployer ?: null,
                'created_by_admin_id' => Auth::guard('admin')->id(),
            ]);
        });

        session()->flash('success', 'Job seeker created successfully.');
        $this->redirect(route('admin.job-seekers.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.job-seekers.job-seeker-create');
    }
}
