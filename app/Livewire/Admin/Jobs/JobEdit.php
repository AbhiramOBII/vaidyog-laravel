<?php

namespace App\Livewire\Admin\Jobs;

use App\Enums\EmploymentTypeEnum;
use App\Models\JobCategory;
use App\Models\JobPosting;
use App\Models\JobSubcategory;
use App\Models\Specialty;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('components.layouts.admin', ['pageTitle' => 'Edit Job'])]
class JobEdit extends Component
{
    use WithFileUploads;

    public JobPosting $job;
    public bool $overrideApproval = false;
    public string $existingThumbnail = '';

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

    public function mount(JobPosting $job): void
    {
        $this->job = $job;
        $this->existingThumbnail = $job->thumbnail ?? '';
        $this->jobTitle = $job->job_title;
        $this->employmentType = $job->employment_type->value;
        $this->numberOfVacancies = $job->number_of_vacancies;
        $this->categorySlug = $job->category_slug ?? '';
        $this->subcategoryName = $job->subcategory_name ?? '';
        $this->specialtyId = $job->specialty_id ? (string) $job->specialty_id : '';
        $this->experienceMin = $job->experience_min !== null ? (string) $job->experience_min : '';
        $this->experienceMax = $job->experience_max !== null ? (string) $job->experience_max : '';
        $this->salaryDisclosed = $job->salary_disclosed;
        $this->salaryMin = $job->salary_min !== null ? (string) $job->salary_min : '';
        $this->salaryMax = $job->salary_max !== null ? (string) $job->salary_max : '';
        $this->postingDurationDays = $job->posting_duration_days;
        $this->jobDescription = $job->job_description;
        $this->keySkills = $job->key_skills ?? [];
        $this->educationalRequirements = $job->educational_requirements ?? [];
        $this->medicalQualifications = $job->medical_qualifications ?? [];
        $this->certificationsRequired = $job->certifications_required ?? [];
        $this->specialties = $job->specialties ?? [];
        $this->locationCity = $job->location_city ?? '';
        $this->locationState = $job->location_state ?? '';
        $this->locationOfficeAddress = $job->location_office_address ?? '';
        $this->locationPincode = $job->location_pincode ?? '';
        $this->isRemote = $job->is_remote;
        $this->contactName = $job->contact_name ?? '';
        $this->contactEmail = $job->contact_email ?? '';
        $this->contactPhone = $job->contact_phone ?? '';
        $this->perksAndBenefits = $job->perks_and_benefits ?? [];
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
        $category = \App\Models\JobCategory::where('slug', $this->categorySlug)->first();
        if (!$category) return [];
        return JobSubcategory::where('job_category_id', $category->id)->orderBy('sort_order')->get()->toArray();
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

        DB::transaction(function () {
            $thumbnailPath = $this->thumbnail
                ? $this->thumbnail->store('job-thumbnails', 'public')
                : ($this->existingThumbnail ?: null);

            $data = [
                'job_title' => $this->jobTitle,
                'thumbnail' => $thumbnailPath,
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
            ];

            // Override approval: approve while editing
            if ($this->overrideApproval && !$this->job->admin_approved) {
                $data['admin_approved'] = true;
                $data['approved_at'] = now();
                $data['approved_by_admin_id'] = Auth::guard('admin')->id();
                $data['rejection_reason'] = null;
                $data['rejected_at'] = null;
                $data['rejected_by_admin_id'] = null;
                $data['expires_at'] = now()->addDays((int) $this->postingDurationDays);
            }

            $this->job->update($data);
        });

        session()->flash('success', 'Job updated successfully.');
        $this->redirect(route('admin.jobs.show', $this->job), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.jobs.job-edit', [
            'employmentTypes' => EmploymentTypeEnum::cases(),
        ]);
    }
}
