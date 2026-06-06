<?php

namespace App\Livewire\JobSeeker\Profile;

use App\Models\City;
use App\Models\Country;
use App\Models\Designation;
use App\Models\JobSeekerProfile;
use App\Models\Specialty;
use App\Models\State;
use App\Models\SubDesignation;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

#[Layout('components.layouts.app', ['pageTitle' => 'Edit Profile'])]
class ProfileEdit extends Component
{
    use WithFileUploads;

    // Personal Info
    public string $salutation = '';
    public string $first_name = '';
    public string $last_name = '';
    public ?string $date_of_birth = null;
    public string $gender = '';
    public string $phone = '';
    public string $email = '';
    public string $nationality = 'Indian';
    public string $country = 'India';
    public string $state = '';
    public string $city = '';
    public string $pincode = '';

    // Professional Info
    public string $designation = '';
    public string $subdesignation = '';
    public ?string $experience_years = null;
    public string $current_employer = '';
    public string $highest_qualification = '';
    public string $category_slug = '';
    public string $category_name = '';
    public string $subcategory_name = '';
    public string $specialty_id = '';
    public array $skills = [];
    public string $skillInput = '';
    public string $about = '';
    public bool $is_open_to_work = true;

    // File uploads
    public $profilePicture = null;
    public $resume = null;

    // UI state
    public bool $personalSaved = false;
    public bool $professionalSaved = false;
    public ?string $currentProfilePicture = null;
    public ?string $currentResume = null;

    // Dropdowns
    public array $designations = [];
    public array $subDesignations = [];
    public array $jobCategories = [];
    public array $jobSubcategories = [];
    public array $specialtiesList = [];

    public function mount(): void
    {
        $user = auth()->user();
        $profile = $user->jobSeekerProfile;

        if ($profile) {
            $this->salutation = $profile->salutation ?? '';
            $this->first_name = $profile->first_name ?? '';
            $this->last_name = $profile->last_name ?? '';
            $this->date_of_birth = $profile->date_of_birth?->format('Y-m-d');
            $this->gender = $profile->gender ?? '';
            $this->phone = $profile->phone ?? $user->phone ?? '';
            $this->email = $profile->email ?? $user->email ?? '';
            $this->nationality = $profile->nationality ?? 'Indian';
            $this->country = $profile->country ?? 'India';
            $this->state = $profile->state ?? '';
            $this->city = $profile->city ?? '';
            $this->pincode = $profile->pincode ?? '';
            $this->designation = $profile->designation ?? '';
            $this->subdesignation = $profile->subdesignation ?? '';
            $this->experience_years = $profile->experience_years;
            $this->current_employer = $profile->current_employer ?? '';
            $this->highest_qualification = $profile->highest_qualification ?? '';
            $this->category_slug = $profile->category_slug ?? '';
            $this->category_name = $profile->category_name ?? '';
            $this->subcategory_name = $profile->subcategory_name ?? '';
            $this->specialty_id = $profile->specialty_id ? (string) $profile->specialty_id : '';
            $this->skills = $profile->key_skills ?? [];
            $this->about = $profile->about ?? '';
            $this->is_open_to_work = $profile->is_open_to_work ?? true;
            $this->currentProfilePicture = $profile->profile_photo_path;
            $this->currentResume = $profile->resume_path;
        } else {
            $this->email = $user->email ?? '';
            $this->phone = $user->phone ?? '';
        }

        $this->designations = Designation::active()->orderBy('sort_order')->pluck('name')->toArray();
        $this->loadSubDesignations();
        $this->jobCategories = \App\Models\JobCategory::orderBy('name')->pluck('name', 'slug')->toArray();
        $this->loadJobSubcategories();
        $this->specialtiesList = Specialty::active()->ordered()->get()->toArray();
    }

    public function updatedCountry(): void
    {
        $this->state = '';
        $this->city = '';
    }

    public function updatedState(): void
    {
        $this->city = '';
    }

    public function updatedDesignation(): void
    {
        $this->subdesignation = '';
        $this->loadSubDesignations();
    }

    public function updatedCategorySlug(): void
    {
        $this->subcategory_name = '';
        $cat = \App\Models\JobCategory::where('slug', $this->category_slug)->first();
        $this->category_name = $cat?->name ?? '';
        $this->loadJobSubcategories();
    }

    public function loadJobSubcategories(): void
    {
        if ($this->category_slug) {
            $cat = \App\Models\JobCategory::where('slug', $this->category_slug)->first();
            $this->jobSubcategories = $cat
                ? $cat->subcategories()->orderBy('name')->pluck('name')->toArray()
                : [];
        } else {
            $this->jobSubcategories = [];
        }
    }

    public function loadSubDesignations(): void
    {
        if ($this->designation) {
            $designationModel = Designation::where('name', $this->designation)->first();
            $this->subDesignations = $designationModel
                ? $designationModel->subDesignations()->active()->orderBy('sort_order')->pluck('name')->toArray()
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

    public function savePersonalInfo(): void
    {
        $validated = $this->validate([
            'salutation' => 'nullable|in:Mr,Mrs,Ms,Dr,Prof',
            'first_name' => 'required|max:60',
            'last_name' => 'required|max:60',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'nullable|in:male,female,other,prefer_not_to_say',
            'phone' => 'required|digits:10',
            'country' => 'required|max:100',
            'state' => 'required|max:100',
            'city' => 'required|max:100',
            'pincode' => 'nullable|digits:6',
            'nationality' => 'required|max:60',
        ]);

        $profile = $this->getOrCreateProfile();
        $profile->update([
            'salutation' => $this->salutation ?: null,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'date_of_birth' => $this->date_of_birth,
            'gender' => $this->gender ?: null,
            'phone' => $this->phone,
            'email' => $this->email,
            'country' => $this->country,
            'state' => $this->state,
            'city' => $this->city,
            'pincode' => $this->pincode ?: null,
            'nationality' => $this->nationality,
        ]);

        // Generate profile slug if not set
        if (!$profile->profile_slug) {
            $profile->update(['profile_slug' => $profile->generateProfileSlug()]);
        }

        $this->personalSaved = true;
        $this->dispatch('saved-personal');
    }

    public function saveProfessionalInfo(): void
    {
        $this->validate([
            'designation' => 'required|max:150',
            'subdesignation' => 'required|max:150',
            'experience_years' => 'nullable|numeric|min:0|max:60',
            'current_employer' => 'nullable|max:150',
            'highest_qualification' => 'nullable|max:150',
            'category_slug' => 'nullable|max:80',
            'subcategory_name' => 'nullable|max:150',
            'specialty_id' => 'nullable|exists:specialties,id',
            'skills' => 'array|min:1|max:30',
            'skills.*' => 'string|max:80',
            'about' => 'nullable|min:50|max:1000',
        ]);

        $profile = $this->getOrCreateProfile();
        $profile->update([
            'designation' => $this->designation,
            'subdesignation' => $this->subdesignation,
            'experience_years' => $this->experience_years ?: null,
            'current_employer' => $this->current_employer ?: null,
            'highest_qualification' => $this->highest_qualification ?: null,
            'category_slug' => $this->category_slug ?: 'general',
            'category_name' => $this->category_name ?: 'General',
            'subcategory_name' => $this->subcategory_name ?: 'General',
            'specialty_id' => $this->specialty_id ?: null,
            'key_skills' => $this->skills,
            'about' => $this->about ?: null,
            'is_open_to_work' => $this->is_open_to_work,
        ]);

        $this->professionalSaved = true;
        $this->dispatch('saved-professional');
    }

    public function uploadProfilePicture(): void
    {
        $this->validate([
            'profilePicture' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        try {
            $profile = $this->getOrCreateProfile();

            if ($profile->profile_photo_path) {
                Storage::disk('public')->delete($profile->profile_photo_path);
            }

            $path = $this->profilePicture->store('profile-pictures', 'public');
            $profile->update(['profile_photo_path' => $path]);
            $this->currentProfilePicture = $path;
            $this->profilePicture = null;
        } catch (\Exception $e) {
            $this->addError('profilePicture', 'Upload failed: ' . $e->getMessage());
        }
    }

    public function removeProfilePicture(): void
    {
        $profile = auth()->user()->jobSeekerProfile;
        if ($profile && $profile->profile_photo_path) {
            Storage::disk('public')->delete($profile->profile_photo_path);
            $profile->update(['profile_photo_path' => null]);
            $this->currentProfilePicture = null;
        }
    }

    public function uploadResume(): void
    {
        $this->validate([
            'resume' => 'required|mimes:pdf|max:5120',
        ]);

        $profile = $this->getOrCreateProfile();

        if ($profile->resume_path) {
            Storage::disk('public')->delete($profile->resume_path);
        }

        $path = $this->resume->store('resumes', 'public');
        $profile->update(['resume_path' => $path]);
        $this->currentResume = $path;
        $this->resume = null;
    }

    public function removeResume(): void
    {
        $profile = auth()->user()->jobSeekerProfile;
        if ($profile && $profile->resume_path) {
            Storage::disk('public')->delete($profile->resume_path);
            $profile->update(['resume_path' => null]);
            $this->currentResume = null;
        }
    }

    private function getOrCreateProfile(): JobSeekerProfile
    {
        $user = auth()->user();
        return $user->jobSeekerProfile ?? JobSeekerProfile::create([
            'user_id' => $user->id,
            'category_slug' => 'general',
            'category_name' => 'General',
            'subcategory_name' => 'General',
        ]);
    }

    #[Computed]
    public function countries()
    {
        return Country::active()->orderBy('sort_order')->orderBy('name')->get();
    }

    #[Computed]
    public function states()
    {
        if (!$this->country) return collect();
        return State::active()
            ->whereHas('country', fn($q) => $q->where('name', $this->country))
            ->orderBy('sort_order')->orderBy('name')->get();
    }

    #[Computed]
    public function cities()
    {
        if (!$this->state) return collect();
        return City::active()
            ->whereHas('state', fn($q) => $q->where('name', $this->state))
            ->orderBy('sort_order')->orderBy('name')->get();
    }

    public function render()
    {
        return view('livewire.job-seeker.profile.profile-edit');
    }
}
