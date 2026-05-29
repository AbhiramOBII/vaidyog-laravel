<?php

namespace App\Livewire\Recruiter\Settings;

use App\Enums\MedTypeEnum;
use App\Models\MedicalInstitution;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('components.layouts.recruiter', ['pageTitle' => 'Settings'])]
class RecruiterSettings extends Component
{
    use WithFileUploads;

    // Account
    public string $name = '';
    public string $email = '';
    public string $phone = '';

    // Institution
    public string $medType = '';
    public string $contactPersonName = '';
    public string $contactPersonEmail = '';
    public string $contactPersonPhone = '';
    public string $websiteUrl = '';
    public string $description = '';

    // Address
    public string $addressLine1 = '';
    public string $addressLine2 = '';
    public string $city = '';
    public string $state = '';
    public string $pincode = '';

    // Social Media
    public string $socialFacebook = '';
    public string $socialTwitter = '';
    public string $socialLinkedin = '';
    public string $socialInstagram = '';
    public string $socialYoutube = '';

    // Logo
    public $logo = null;
    public ?string $existingLogo = null;

    // Password change
    public string $currentPassword = '';
    public string $newPassword = '';
    public string $newPassword_confirmation = '';

    public bool $showSuccess = false;
    public bool $showPasswordSuccess = false;

    protected function recruiter(): MedicalInstitution
    {
        return MedicalInstitution::withoutGlobalScopes()->find(auth()->id());
    }

    public function mount(): void
    {
        $user = $this->recruiter();
        $profile = $user->profile;

        $this->name = $user->name ?? '';
        $this->email = $user->email ?? '';
        $this->phone = $user->phone ?? '';

        $this->medType = $profile?->med_type?->value ?? '';
        $this->contactPersonName = $profile?->contact_person_name ?? '';
        $this->contactPersonEmail = $profile?->contact_person_email ?? '';
        $this->contactPersonPhone = $profile?->contact_person_phone ?? '';
        $this->websiteUrl = $profile?->website_url ?? '';
        $this->description = $profile?->description ?? '';

        $this->addressLine1 = $profile?->address_line1 ?? '';
        $this->addressLine2 = $profile?->address_line2 ?? '';
        $this->city = $profile?->city ?? '';
        $this->state = $profile?->state ?? '';
        $this->pincode = $profile?->pincode ?? '';

        $this->socialFacebook = $profile?->social_facebook ?? '';
        $this->socialTwitter = $profile?->social_twitter ?? '';
        $this->socialLinkedin = $profile?->social_linkedin ?? '';
        $this->socialInstagram = $profile?->social_instagram ?? '';
        $this->socialYoutube = $profile?->social_youtube ?? '';

        $this->existingLogo = $profile?->logo_path;
    }

    public function saveProfile(): void
    {
        $user = $this->recruiter();

        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'digits_between:10,15'],
            'medType' => ['required', Rule::in(array_column(MedTypeEnum::cases(), 'value'))],
            'contactPersonName' => ['nullable', 'string', 'max:255'],
            'contactPersonEmail' => ['nullable', 'email', 'max:255'],
            'contactPersonPhone' => ['nullable', 'string', 'digits_between:10,15'],
            'websiteUrl' => ['nullable', 'url', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'socialFacebook' => ['nullable', 'url', 'max:500'],
            'socialTwitter' => ['nullable', 'url', 'max:500'],
            'socialLinkedin' => ['nullable', 'url', 'max:500'],
            'socialInstagram' => ['nullable', 'url', 'max:500'],
            'socialYoutube' => ['nullable', 'url', 'max:500'],
            'addressLine1' => ['nullable', 'string', 'max:255'],
            'addressLine2' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'pincode' => ['nullable', 'string', 'regex:/^\d{6}$/'],
            'logo' => ['nullable', 'image', 'max:2048'],
        ]);

        $user->update([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone ?: null,
        ]);

        $logoPath = $this->existingLogo;
        if ($this->logo) {
            $logoPath = $this->logo->store('recruiter-logos', 'public');
        }

        $profile = $user->profile;
        $profileData = [
            'institution_name' => $this->name,
            'med_type' => $this->medType,
            'contact_person_name' => $this->contactPersonName ?: null,
            'contact_person_email' => $this->contactPersonEmail ?: null,
            'contact_person_phone' => $this->contactPersonPhone ?: null,
            'website_url' => $this->websiteUrl ?: null,
            'description' => $this->description ?: null,
            'social_facebook' => $this->socialFacebook ?: null,
            'social_twitter' => $this->socialTwitter ?: null,
            'social_linkedin' => $this->socialLinkedin ?: null,
            'social_instagram' => $this->socialInstagram ?: null,
            'social_youtube' => $this->socialYoutube ?: null,
            'address_line1' => $this->addressLine1 ?: null,
            'address_line2' => $this->addressLine2 ?: null,
            'city' => $this->city ?: null,
            'state' => $this->state ?: null,
            'pincode' => $this->pincode ?: null,
            'logo_path' => $logoPath,
        ];

        if ($profile) {
            $profile->update($profileData);
        } else {
            $user->profile()->create(array_merge($profileData, [
                'user_id' => $user->id,
                'med_type' => $this->medType ?: \App\Enums\MedTypeEnum::Clinics->value,
            ]));
        }

        $this->existingLogo = $logoPath;
        $this->logo = null;
        $this->showSuccess = true;
    }

    public function changePassword(): void
    {
        $this->validate([
            'currentPassword' => ['required'],
            'newPassword' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'newPassword.confirmed' => 'The new passwords do not match.',
        ]);

        $user = $this->recruiter();

        if (!Hash::check($this->currentPassword, $user->password)) {
            $this->addError('currentPassword', 'Current password is incorrect.');
            return;
        }

        $user->update(['password' => Hash::make($this->newPassword)]);

        $this->currentPassword = '';
        $this->newPassword = '';
        $this->newPassword_confirmation = '';
        $this->showPasswordSuccess = true;
    }

    public function removeLogo(): void
    {
        $this->existingLogo = null;
        $this->logo = null;

        $user = $this->recruiter();
        $user->profile?->update(['logo_path' => null]);
    }

    public function render()
    {
        return view('livewire.recruiter.settings.recruiter-settings', [
            'medTypes' => MedTypeEnum::cases(),
        ]);
    }
}
