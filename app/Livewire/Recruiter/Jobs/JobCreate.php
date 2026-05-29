<?php

namespace App\Livewire\Recruiter\Jobs;

use App\Enums\EmploymentTypeEnum;
use App\Models\City;
use App\Models\JobCategory;
use App\Models\JobPosting;
use App\Models\JobSubcategory;
use App\Models\Specialty;
use App\Models\StandardTag;
use App\Models\State;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('components.layouts.recruiter', ['pageTitle' => 'Post New Job'])]
class JobCreate extends Component
{
    use WithFileUploads;
    // Section 1: Basics
    public string $jobTitle = '';
    public string $employmentType = 'full_time';
    public int $numberOfVacancies = 1;
    public string $categorySlug = '';
    public string $subcategoryName = '';
    public string $specialtyId = '';

    // Section 2: Experience & Salary
    public string $experienceMin = '';
    public string $experienceMax = '';
    public bool $salaryDisclosed = true;
    public string $salaryMin = '';
    public string $salaryMax = '';
    public int $postingDurationDays = 30;

    // Thumbnail
    public $thumbnail = null;

    // Section 3: Description & Skills
    public string $jobDescription = '';
    public array $keySkills = [];
    public array $educationalRequirements = [];
    public array $medicalQualifications = [];
    public array $certificationsRequired = [];
    public array $specialties = [];

    // Section 4: Location
    public string $locationCity = '';
    public string $locationState = '';
    public string $locationOfficeAddress = '';
    public string $locationPincode = '';
    public bool $isRemote = false;

    // Section 5: Contact
    public string $contactName = '';
    public string $contactEmail = '';
    public string $contactPhone = '';

    // Section 6: Perks
    public array $perksAndBenefits = [];

    public function mount(): void
    {
        $user = Auth::user();
        $profile = $user->profile ?? null;
        if ($profile) {
            $this->contactName = $profile->contact_person_name ?? '';
            $this->contactEmail = $user->email ?? '';
            $this->contactPhone = $profile->contact_person_phone ?? $user->phone ?? '';
        }
    }

    #[Computed]
    public function categories(): array
    {
        return JobCategory::orderBy('sort_order')->get()->toArray();
    }

    #[Computed]
    public function subcategories(): array
    {
        if (!$this->categorySlug) return [];
        $category = JobCategory::where('slug', $this->categorySlug)->first();
        if (!$category) return [];
        return JobSubcategory::where('job_category_id', $category->id)->orderBy('sort_order')->get()->toArray();
    }

    #[Computed]
    public function specialtiesList(): array
    {
        return Specialty::active()->ordered()->get()->toArray();
    }

    #[Computed]
    public function standardKeySkills(): array
    {
        return StandardTag::getByType(StandardTag::TYPE_KEY_SKILL);
    }

    #[Computed]
    public function standardEducationalRequirements(): array
    {
        return StandardTag::getByType(StandardTag::TYPE_EDUCATIONAL_REQUIREMENT);
    }

    #[Computed]
    public function standardMedicalQualifications(): array
    {
        return StandardTag::getByType(StandardTag::TYPE_MEDICAL_QUALIFICATION);
    }

    #[Computed]
    public function standardCertifications(): array
    {
        return StandardTag::getByType(StandardTag::TYPE_CERTIFICATION);
    }

    #[Computed]
    public function statesList(): array
    {
        return State::active()->orderBy('name')->pluck('name')->toArray();
    }

    #[Computed]
    public function citiesList(): array
    {
        if (!$this->locationState) return [];
        $state = State::where('name', $this->locationState)->first();
        if (!$state) return [];
        return City::where('state_id', $state->id)->active()->orderBy('name')->pluck('name')->toArray();
    }

    public function updatedCategorySlug(): void
    {
        $this->subcategoryName = '';
    }

    public function updatedLocationState(): void
    {
        $this->locationCity = '';
    }

    public function rules(): array
    {
        return [
            'jobTitle' => ['required', 'string', 'max:150'],
            'employmentType' => ['required', Rule::in(array_column(EmploymentTypeEnum::cases(), 'value'))],
            'numberOfVacancies' => ['required', 'integer', 'min:1', 'max:500'],
            'categorySlug' => ['required', 'exists:job_categories,slug'],
            'subcategoryName' => ['required', 'string'],
            'specialtyId' => ['nullable', 'exists:specialties,id'],
            'experienceMin' => ['nullable', 'numeric', 'min:0', 'max:50'],
            'experienceMax' => ['nullable', 'numeric', 'min:0', 'max:50', 'gte:experienceMin'],
            'salaryMin' => ['nullable', 'integer', 'min:0'],
            'salaryMax' => ['nullable', 'integer', 'min:0', 'gte:salaryMin'],
            'postingDurationDays' => ['required', Rule::in([15, 30, 60, 90])],
            'thumbnail' => ['nullable', 'image', 'max:2048'],
            'jobDescription' => ['required', 'string', 'min:100'],
            'keySkills' => ['array', 'max:20'],
            'keySkills.*' => ['string', 'max:80'],
            'locationCity' => ['nullable', 'string', 'max:100'],
            'locationState' => ['nullable', 'string', 'max:100'],
            'contactName' => ['nullable', 'string', 'max:255'],
            'contactEmail' => ['nullable', 'email', 'max:255'],
            'contactPhone' => ['nullable', 'string', 'max:20'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $user = Auth::user();
        $profile = $user->profile;

        DB::transaction(function () use ($user, $profile) {
            JobPosting::create([
                'recruiter_id' => $user->id,
                'job_title' => $this->jobTitle,
                'thumbnail' => $this->thumbnail ? $this->thumbnail->store('job-thumbnails', 'public') : null,
                'job_description' => $this->jobDescription,
                'key_skills' => $this->keySkills ?: null,
                'employment_type' => $this->employmentType,
                'experience_min' => $this->experienceMin !== '' ? $this->experienceMin : null,
                'experience_max' => $this->experienceMax !== '' ? $this->experienceMax : null,
                'location_city' => $this->locationCity ?: null,
                'location_state' => $this->locationState ?: null,
                'location_office_address' => $this->locationOfficeAddress ?: null,
                'location_pincode' => $this->locationPincode ?: null,
                'is_remote' => $this->isRemote,
                'salary_min' => $this->salaryDisclosed && $this->salaryMin ? (int) $this->salaryMin : null,
                'salary_max' => $this->salaryDisclosed && $this->salaryMax ? (int) $this->salaryMax : null,
                'salary_disclosed' => $this->salaryDisclosed,
                'institution_name' => $profile?->institution_name ?? $user->name,
                'contact_name' => $this->contactName ?: null,
                'contact_email' => $this->contactEmail ?: null,
                'contact_phone' => $this->contactPhone ?: null,
                'educational_requirements' => $this->educationalRequirements ?: null,
                'medical_qualifications' => $this->medicalQualifications ?: null,
                'certifications_required' => $this->certificationsRequired ?: null,
                'specialties' => $this->specialties ?: null,
                'perks_and_benefits' => $this->perksAndBenefits ?: null,
                'posting_duration_days' => $this->postingDurationDays,
                'number_of_vacancies' => $this->numberOfVacancies,
                'category_slug' => $this->categorySlug,
                'subcategory_name' => $this->subcategoryName,
                'specialty_id' => $this->specialtyId ?: null,
                'admin_approved' => false,
                'is_active' => true,
            ]);
        });

        session()->flash('success', 'Job posted successfully! Our AI engine identified top matching candidates.');
        $this->dispatch('job-created');
    }

    public function render()
    {
        return view('livewire.recruiter.jobs.job-create', [
            'employmentTypes' => EmploymentTypeEnum::cases(),
        ]);
    }
}
