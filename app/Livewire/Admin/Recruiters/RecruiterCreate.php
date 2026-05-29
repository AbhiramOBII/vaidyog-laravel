<?php

namespace App\Livewire\Admin\Recruiters;

use App\Enums\AuthProviderEnum;
use App\Enums\MedTypeEnum;
use App\Enums\UserStatusEnum;
use App\Models\MedicalInstitution;
use App\Models\MedicalInstitutionProfile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('components.layouts.admin')]
class RecruiterCreate extends Component
{
    use WithFileUploads;

    // Logo
    public $logo = null;

    // Section 1 — Account
    public string $institutionName = '';
    public string $email = '';
    public string $tempPassword = '';
    public string $phone = '';
    public string $authMethod = 'email';
    public string $accountStatus = 'active';
    public bool $emailVerified = false;

    // Section 2 — Institution
    public string $medType = '';
    public string $industryType = '';
    public string $contactPersonName = '';
    public string $contactPersonEmail = '';
    public string $contactPersonPhone = '';
    public string $websiteUrl = '';

    // Section 3 — Profile
    public string $description = '';
    public string $employeeCount = '';
    public array $specialties = [];
    public array $accreditations = [];
    public string $specialtyInput = '';
    public string $accreditationInput = '';

    // Section 4 — Address
    public string $addressLine1 = '';
    public string $addressLine2 = '';
    public string $city = '';
    public string $state = '';
    public string $pincode = '';

    // Section 5 — Flags
    public bool $isFeatured = false;
    public bool $profileCompleted = false;

    public function addSpecialty(): void
    {
        $value = trim($this->specialtyInput);
        if ($value && !in_array($value, $this->specialties) && count($this->specialties) < 20) {
            $this->specialties[] = $value;
        }
        $this->specialtyInput = '';
    }

    public function removeSpecialty(int $index): void
    {
        unset($this->specialties[$index]);
        $this->specialties = array_values($this->specialties);
    }

    public function addAccreditation(): void
    {
        $value = trim($this->accreditationInput);
        if ($value && !in_array($value, $this->accreditations) && count($this->accreditations) < 20) {
            $this->accreditations[] = $value;
        }
        $this->accreditationInput = '';
    }

    public function removeAccreditation(int $index): void
    {
        unset($this->accreditations[$index]);
        $this->accreditations = array_values($this->accreditations);
    }

    public function rules(): array
    {
        return [
            'institutionName' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'tempPassword' => [$this->authMethod === 'email' ? 'required' : 'nullable', 'string', 'min:8', 'max:100'],
            'phone' => ['nullable', 'string', 'digits_between:10,15'],
            'authMethod' => ['required', Rule::in(['email', 'google'])],
            'accountStatus' => ['required', Rule::in(array_column(UserStatusEnum::cases(), 'value'))],
            'medType' => ['required', Rule::in(array_column(MedTypeEnum::cases(), 'value'))],
            'websiteUrl' => ['nullable', 'url', 'max:255'],
            'contactPersonEmail' => ['nullable', 'email', 'max:255'],
            'contactPersonPhone' => ['nullable', 'string', 'digits_between:10,15'],
            'pincode' => ['nullable', 'string', 'regex:/^\d{6}$/'],
            'logo' => ['nullable', 'image', 'max:2048'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        DB::transaction(function () {
            $user = MedicalInstitution::withoutGlobalScopes()->create([
                'name' => $this->institutionName,
                'email' => $this->email,
                'phone' => $this->phone ?: null,
                'password' => ($this->authMethod === 'email' && $this->tempPassword) ? $this->tempPassword : null,
                'user_type' => 'MedicalInstitution',
                'status' => $this->accountStatus,
                'auth_provider' => $this->authMethod,
                'is_active' => $this->accountStatus === 'active',
                'is_profile_completed' => $this->profileCompleted,
                'email_verified_at' => $this->emailVerified ? now() : null,
            ]);

            $logoPath = $this->logo ? $this->logo->store('recruiter-logos', 'public') : null;

            MedicalInstitutionProfile::create([
                'user_id' => $user->id,
                'institution_name' => $this->institutionName,
                'logo_path' => $logoPath,
                'industry_type' => $this->industryType ?: null,
                'med_type' => $this->medType,
                'contact_person_name' => $this->contactPersonName ?: null,
                'contact_person_email' => $this->contactPersonEmail ?: null,
                'contact_person_phone' => $this->contactPersonPhone ?: null,
                'description' => $this->description ?: null,
                'employee_count' => $this->employeeCount !== '' ? (int) $this->employeeCount : null,
                'specialties' => !empty($this->specialties) ? $this->specialties : null,
                'accreditations' => !empty($this->accreditations) ? $this->accreditations : null,
                'address_line1' => $this->addressLine1 ?: null,
                'address_line2' => $this->addressLine2 ?: null,
                'city' => $this->city ?: null,
                'state' => $this->state ?: null,
                'pincode' => $this->pincode ?: null,
                'website_url' => $this->websiteUrl ?: null,
                'is_featured' => $this->isFeatured,
                'featured_at' => $this->isFeatured ? now() : null,
                'is_profile_completed' => $this->profileCompleted,
                'created_by_admin_id' => Auth::guard('admin')->id(),
            ]);
        });

        session()->flash('message', 'Recruiter created successfully.');
        $this->redirect(route('admin.recruiters.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.recruiters.recruiter-create', [
            'medTypes' => MedTypeEnum::cases(),
            'statuses' => UserStatusEnum::cases(),
        ]);
    }
}
