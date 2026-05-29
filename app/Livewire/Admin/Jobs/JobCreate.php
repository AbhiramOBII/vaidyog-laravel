<?php

namespace App\Livewire\Admin\Jobs;

use App\Enums\EmploymentTypeEnum;
use App\Models\JobCategory;
use App\Models\JobPosting;
use App\Models\JobSubcategory;
use App\Models\MedicalInstitution;
use App\Models\Specialty;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('components.layouts.admin', ['pageTitle' => 'Post New Job'])]
class JobCreate extends Component
{
    use WithFileUploads;

    public bool $autoApprove = true;
    public string $recruiterId = '';
    public $thumbnail = null;

    public string $jobTitle = '';
    public string $employmentType = 'full_time';
    public int $numberOfVacancies = 1;
    public string $categorySlug = '';
    public string $subcategoryName = '';
    public string $specialtyId = '';
    public string $experienceMin = '';
    public string $experienceMax = '';
    public bool $salaryDisclosed = true;
    public string $salaryMin = '';
    public string $salaryMax = '';
    public int $postingDurationDays = 30;
    public string $jobDescription = '';
    public array $keySkills = [];
    public array $educationalRequirements = [];
    public array $medicalQualifications = [];
    public array $certificationsRequired = [];
    public array $specialties = [];
    public string $locationCity = '';
    public string $locationState = '';
    public string $locationOfficeAddress = '';
    public string $locationPincode = '';
    public bool $isRemote = false;
    public string $contactName = '';
    public string $contactEmail = '';
    public string $contactPhone = '';
    public array $perksAndBenefits = [];

    #[Computed]
    public function categories(): array
    {
        return JobCategory::orderBy('sort_order')->get()->toArray();
    }

    #[Computed]
    public function subcategories(): array
    {
        if (!$this->categorySlug) return [];
        return JobSubcategory::where('category_slug', $this->categorySlug)->orderBy('sort_order')->get()->toArray();
    }

    #[Computed]
    public function recruiters(): array
    {
        return MedicalInstitution::select('id', 'name', 'email')->orderBy('name')->limit(100)->get()->toArray();
    }

    #[Computed]
    public function specialtiesList(): array
    {
        return Specialty::active()->ordered()->get()->toArray();
    }

    public function updatedCategorySlug(): void { $this->subcategoryName = ''; }

    public function rules(): array
    {
        return [
            'recruiterId' => ['required', 'exists:users,id'],
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
        ];
    }

    public function save(): void
    {
        $this->validate();

        $recruiter = MedicalInstitution::with('profile')->findOrFail($this->recruiterId);

        DB::transaction(function () use ($recruiter) {
            $job = JobPosting::create([
                'recruiter_id' => $recruiter->id,
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
                'institution_name' => $recruiter->profile?->institution_name ?? $recruiter->name,
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
                'admin_approved' => $this->autoApprove,
                'approved_at' => $this->autoApprove ? now() : null,
                'approved_by_admin_id' => $this->autoApprove ? Auth::guard('admin')->id() : null,
                'expires_at' => $this->autoApprove ? now()->addDays($this->postingDurationDays) : null,
                'is_active' => true,
            ]);
        });

        session()->flash('success', 'Job created successfully.');
        $this->redirect(route('admin.jobs.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.jobs.job-create', [
            'employmentTypes' => EmploymentTypeEnum::cases(),
        ]);
    }
}
