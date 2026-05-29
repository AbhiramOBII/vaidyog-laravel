<?php

namespace App\Livewire\Admin\Recruiters;

use App\Enums\MedTypeEnum;
use App\Enums\UserStatusEnum;
use App\Models\AdminActionLog;
use App\Models\MedicalInstitution;
use App\Services\ReferralCodeService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('components.layouts.admin')]
class RecruiterEdit extends Component
{
    use WithFileUploads;

    public MedicalInstitution $recruiter;
    public $logo = null;
    public string $existingLogo = '';

    // Account
    public string $institutionName = '';
    public string $email = '';
    public string $phone = '';
    public string $authMethod = 'email';
    public string $accountStatus = 'active';
    public bool $emailVerified = false;

    // Institution
    public string $medType = '';
    public string $industryType = '';
    public string $contactPersonName = '';
    public string $contactPersonEmail = '';
    public string $contactPersonPhone = '';
    public string $websiteUrl = '';

    // Profile
    public string $description = '';
    public string $employeeCount = '';
    public array $specialties = [];
    public array $accreditations = [];
    public string $specialtyInput = '';
    public string $accreditationInput = '';

    // Address
    public string $addressLine1 = '';
    public string $addressLine2 = '';
    public string $city = '';
    public string $state = '';
    public string $pincode = '';

    // Flags
    public bool $isFeatured = false;
    public bool $profileCompleted = false;
    public string $referralCode = '';

    public function mount(string $user): void
    {
        $this->recruiter = MedicalInstitution::with('profile')->findOrFail($user);
        $profile = $this->recruiter->profile;

        $this->institutionName = $profile?->institution_name ?? '';
        $this->email = $this->recruiter->email ?? '';
        $this->phone = $this->recruiter->phone ?? '';
        $this->authMethod = $this->recruiter->auth_provider?->value ?? 'email';
        $this->accountStatus = $this->recruiter->status->value;
        $this->emailVerified = $this->recruiter->email_verified_at !== null;

        $this->medType = $profile?->med_type?->value ?? '';
        $this->industryType = $profile?->industry_type ?? '';
        $this->contactPersonName = $profile?->contact_person_name ?? '';
        $this->contactPersonEmail = $profile?->contact_person_email ?? '';
        $this->contactPersonPhone = $profile?->contact_person_phone ?? '';
        $this->websiteUrl = $profile?->website_url ?? '';

        $this->description = $profile?->description ?? '';
        $this->employeeCount = $profile?->employee_count ? (string) $profile->employee_count : '';
        $this->specialties = $profile?->specialties ?? [];
        $this->accreditations = $profile?->accreditations ?? [];

        $this->addressLine1 = $profile?->address_line1 ?? '';
        $this->addressLine2 = $profile?->address_line2 ?? '';
        $this->city = $profile?->city ?? '';
        $this->state = $profile?->state ?? '';
        $this->pincode = $profile?->pincode ?? '';

        $this->existingLogo = $profile?->logo_path ?? '';
        $this->isFeatured = $profile?->is_featured ?? false;
        $this->profileCompleted = $profile?->is_profile_completed ?? false;
        $this->referralCode = $profile?->referral_code ?? '';
    }

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

    public function regenerateReferralCode(): void
    {
        $profile = $this->recruiter->profile;
        if (!$profile) return;

        $newCode = ReferralCodeService::generate();
        $profile->update(['referral_code' => $newCode]);
        $this->referralCode = $newCode;

        AdminActionLog::create([
            'admin_id' => Auth::guard('admin')->id(),
            'target_type' => 'MedicalInstitution',
            'target_id' => $this->recruiter->id,
            'action' => 'referral_regenerated',
            'notes' => 'Referral code regenerated.',
        ]);

        session()->flash('message', 'Referral code regenerated.');
    }

    public function setStatus(string $status): void
    {
        $this->recruiter->update(['status' => $status]);
        $this->accountStatus = $status;

        AdminActionLog::create([
            'admin_id' => Auth::guard('admin')->id(),
            'target_type' => 'MedicalInstitution',
            'target_id' => $this->recruiter->id,
            'action' => 'status_change',
            'notes' => "Changed to {$status}",
        ]);

        $this->recruiter->refresh();
        session()->flash('message', 'Status updated.');
    }

    public function rules(): array
    {
        return [
            'institutionName' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->recruiter->id)],
            'phone' => ['nullable', 'string', 'digits_between:10,15'],
            'medType' => ['required', Rule::in(array_column(MedTypeEnum::cases(), 'value'))],
            'websiteUrl' => ['nullable', 'url', 'max:255'],
            'contactPersonEmail' => ['nullable', 'email', 'max:255'],
            'pincode' => ['nullable', 'string', 'regex:/^\d{6}$/'],
            'logo' => ['nullable', 'image', 'max:2048'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        DB::transaction(function () {
            $this->recruiter->update([
                'name' => $this->institutionName,
                'email' => $this->email,
                'phone' => $this->phone ?: null,
                'auth_provider' => $this->authMethod,
                'is_active' => $this->accountStatus === 'active',
                'is_profile_completed' => $this->profileCompleted,
                'email_verified_at' => $this->emailVerified ? ($this->recruiter->email_verified_at ?? now()) : null,
            ]);

            $profile = $this->recruiter->profile;
            $oldMedType = $profile?->med_type?->value;
            $newMedType = MedTypeEnum::from($this->medType);

            $logoPath = $this->logo
                ? $this->logo->store('recruiter-logos', 'public')
                : ($this->existingLogo ?: null);

            $profileData = [
                'logo_path' => $logoPath,
                'institution_name' => $this->institutionName,
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
                'featured_at' => $this->isFeatured ? ($profile?->featured_at ?? now()) : null,
                'is_profile_completed' => $this->profileCompleted,
            ];

            if (!$newMedType->requiresReferralCode()) {
                $profileData['referral_code'] = null;
                $this->referralCode = '';
            }

            if ($profile) {
                $profile->update($profileData);
            }
        });

        session()->flash('message', 'Recruiter updated successfully.');
        $this->redirect(route('admin.recruiters.show', $this->recruiter), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.recruiters.recruiter-edit', [
            'medTypes' => MedTypeEnum::cases(),
            'statuses' => UserStatusEnum::cases(),
        ]);
    }
}
