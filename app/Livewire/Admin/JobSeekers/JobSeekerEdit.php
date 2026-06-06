<?php

namespace App\Livewire\Admin\JobSeekers;

use App\Enums\AuthProviderEnum;
use App\Enums\UserStatusEnum;
use App\Models\AdminActionLog;
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
class JobSeekerEdit extends Component
{
    public User $seeker;

    // Account
    public string $salutation = '';
    public string $name = '';
    public string $phone = '';
    public string $email = '';
    public string $accountStatus = 'active';

    // Classification
    public string $categoryId = '';
    public string $subcategoryId = '';

    // Profile basics
    public string $gender = '';
    public string $dateOfBirth = '';
    public string $city = '';
    public string $state = '';
    public string $pincode = '';
    public string $experienceYears = '';
    public string $highestQualification = '';
    public string $currentEmployer = '';
    public string $designation = '';
    public string $about = '';

    // Toggles
    public bool $profileCompleted = false;

    public function mount(string $user): void
    {
        $this->seeker = User::jobSeekers()->with('jobSeekerProfile')->findOrFail($user);
        $profile = $this->seeker->jobSeekerProfile;

        $this->name = $this->seeker->name ?? '';
        $this->phone = $this->seeker->phone ?? '';
        $this->email = $this->seeker->email ?? '';
        $this->accountStatus = $this->seeker->status->value;

        if ($profile) {
            $this->salutation = $profile->salutation ?? '';
            $category = $profile->category_slug ? JobCategory::where('slug', $profile->category_slug)->first() : null;
            $subcategory = $profile->subcategory_name ? JobSubcategory::where('name', $profile->subcategory_name)->first() : null;

            $this->categoryId = $category?->id ? (string) $category->id : '';
            $this->subcategoryId = $subcategory?->id ? (string) $subcategory->id : '';
            $this->gender = $profile->gender ?? '';
            $this->dateOfBirth = $profile->date_of_birth?->format('Y-m-d') ?? '';
            $this->city = $profile->city ?? '';
            $this->state = $profile->state ?? '';
            $this->pincode = $profile->pincode ?? '';
            $this->experienceYears = $profile->experience_years !== null ? (string) $profile->experience_years : '';
            $this->highestQualification = $profile->highest_qualification ?? '';
            $this->currentEmployer = $profile->current_employer ?? '';
            $this->designation = $profile->designation ?? '';
            $this->about = $profile->about ?? '';
        }

        $this->profileCompleted = $this->seeker->is_profile_completed ?? false;
    }

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
        if (!$this->categoryId) return collect();

        return JobSubcategory::where('job_category_id', $this->categoryId)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get(['id', 'job_category_id', 'name', 'slug']);
    }

    public function rules(): array
    {
        return [
            'salutation' => ['nullable', Rule::in(['Mr', 'Mrs', 'Ms', 'Dr', 'Prof'])],
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'regex:/^[6-9]\d{9}$/', Rule::unique('users', 'phone')->ignore($this->seeker->id)],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->seeker->id)],
            'accountStatus' => ['required', Rule::in(array_column(UserStatusEnum::cases(), 'value'))],
            'categoryId' => ['nullable', 'exists:job_categories,id'],
            'subcategoryId' => ['nullable'],
            'gender' => ['nullable', 'string', Rule::in(['male', 'female', 'other'])],
            'dateOfBirth' => ['nullable', 'date', 'before:today'],
            'city' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'pincode' => ['nullable', 'string', 'regex:/^\d{6}$/'],
            'experienceYears' => ['nullable', 'numeric', 'min:0', 'max:60'],
            'highestQualification' => ['nullable', 'string', 'max:255'],
            'currentEmployer' => ['nullable', 'string', 'max:255'],
            'designation' => ['nullable', 'string', 'max:255'],
            'about' => ['nullable', 'string', 'max:5000'],
        ];
    }

    public function messages(): array
    {
        return [
            'phone.regex' => 'Enter a valid 10-digit Indian mobile number.',
            'pincode.regex' => 'Enter a valid 6-digit pincode.',
        ];
    }

    public function save(): void
    {
        $this->validate();

        $category = $this->categoryId ? JobCategory::find($this->categoryId) : null;
        $subcategory = $this->subcategoryId ? JobSubcategory::find($this->subcategoryId) : null;

        DB::transaction(function () use ($category, $subcategory) {
            $this->seeker->update([
                'name' => $this->name,
                'phone' => $this->phone,
                'email' => $this->email ?: null,
                'status' => $this->accountStatus,
                'is_active' => $this->accountStatus === 'active',
                'is_profile_completed' => $this->profileCompleted,
            ]);

            $profileData = [
                'salutation' => $this->salutation ?: null,
                'category_slug' => $category?->slug,
                'category_name' => $category?->name,
                'subcategory_name' => $subcategory?->name,
                'gender' => $this->gender ?: null,
                'date_of_birth' => $this->dateOfBirth ?: null,
                'city' => $this->city ?: null,
                'state' => $this->state ?: null,
                'pincode' => $this->pincode ?: null,
                'experience_years' => $this->experienceYears !== '' ? $this->experienceYears : null,
                'highest_qualification' => $this->highestQualification ?: null,
                'current_employer' => $this->currentEmployer ?: null,
                'designation' => $this->designation ?: null,
                'about' => $this->about ?: null,
            ];

            if ($this->seeker->jobSeekerProfile) {
                $this->seeker->jobSeekerProfile->update($profileData);
                $profile = $this->seeker->jobSeekerProfile;
            } else {
                $profileData['user_id'] = $this->seeker->id;
                $profileData['created_by_admin_id'] = Auth::guard('admin')->id();
                $profile = JobSeekerProfile::create($profileData);
            }

            if (!$profile->profile_slug) {
                $profile->update(['profile_slug' => $profile->generateProfileSlug()]);
            }
        });

        AdminActionLog::create([
            'admin_id' => Auth::guard('admin')->id(),
            'target_type' => 'User',
            'target_id' => $this->seeker->id,
            'action' => 'edited',
            'notes' => 'Job seeker profile updated by admin.',
        ]);

        session()->flash('message', 'Job seeker updated successfully.');
        $this->redirect(route('admin.job-seekers.show', $this->seeker), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.job-seekers.job-seeker-edit', [
            'statuses' => UserStatusEnum::cases(),
        ]);
    }
}
