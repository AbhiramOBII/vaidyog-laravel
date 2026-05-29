<?php

namespace App\Livewire\Recruiter\Onboarding;

use App\Enums\MedTypeEnum;
use App\Models\MedicalInstitutionProfile;
use App\Models\RecruiterSubscription;
use App\Models\RecruiterSubscriptionPlan;
use App\Models\RecruiterSubscriptionPlanOption;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('components.layouts.guest')]
class RecruiterOnboarding extends Component
{
    use WithFileUploads;

    public int $step = 1; // 1 = Profile, 2 = Plan

    // Profile fields
    public string $institutionName = '';
    public string $contactPersonName = '';
    public string $contactPersonEmail = '';
    public string $contactPersonPhone = '';
    public string $description = '';
    public string $websiteUrl = '';
    public string $addressLine1 = '';
    public string $addressLine2 = '';
    public string $city = '';
    public string $state = '';
    public string $pincode = '';
    public $logo = null;

    // Plan
    public ?string $selectedPlanOptionId = null;

    public function mount(): void
    {
        $user = auth()->user();

        if ($user->is_profile_completed) {
            $this->redirect('/recruiter/dashboard', navigate: true);
            return;
        }

        $this->institutionName = $user->name ?? '';
        $this->contactPersonEmail = $user->email ?? '';
        $this->contactPersonPhone = $user->phone ?? '';

        // Pre-fill from profile if it exists
        $profile = $user->profile;
        if ($profile) {
            $this->institutionName = $profile->institution_name ?? $this->institutionName;
            $this->contactPersonName = $profile->contact_person_name ?? '';
            $this->contactPersonEmail = $profile->contact_person_email ?? $this->contactPersonEmail;
            $this->contactPersonPhone = $profile->contact_person_phone ?? $this->contactPersonPhone;
            $this->description = $profile->description ?? '';
            $this->websiteUrl = $profile->website_url ?? '';
            $this->addressLine1 = $profile->address_line1 ?? '';
            $this->addressLine2 = $profile->address_line2 ?? '';
            $this->city = $profile->city ?? '';
            $this->state = $profile->state ?? '';
            $this->pincode = $profile->pincode ?? '';
        }
    }

    public function submitProfile(): void
    {
        $this->validate([
            'institutionName' => ['required', 'string', 'max:255'],
            'contactPersonName' => ['required', 'string', 'max:255'],
            'contactPersonEmail' => ['nullable', 'email', 'max:255'],
            'contactPersonPhone' => ['nullable', 'string', 'digits_between:10,15'],
            'description' => ['nullable', 'string', 'max:2000'],
            'websiteUrl' => ['nullable', 'url', 'max:255'],
            'addressLine1' => ['nullable', 'string', 'max:255'],
            'addressLine2' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'pincode' => ['nullable', 'string', 'regex:/^\d{6}$/'],
            'logo' => ['nullable', 'image', 'max:2048'],
        ]);

        $user = auth()->user();
        $profile = $user->profile;

        $logoPath = null;
        if ($this->logo) {
            $logoPath = $this->logo->store('recruiter-logos', 'public');
        } elseif ($profile) {
            $logoPath = $profile->logo_path;
        }

        $profileData = [
            'institution_name' => $this->institutionName,
            'contact_person_name' => $this->contactPersonName,
            'contact_person_email' => $this->contactPersonEmail ?: null,
            'contact_person_phone' => $this->contactPersonPhone ?: null,
            'description' => $this->description ?: null,
            'website_url' => $this->websiteUrl ?: null,
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
            MedicalInstitutionProfile::create(array_merge($profileData, [
                'user_id' => $user->id,
                'med_type' => $user->profile?->med_type ?? MedTypeEnum::Clinics,
            ]));
        }

        $user->update(['name' => $this->institutionName]);

        $this->step = 2;
    }

    public function goBack(): void
    {
        $this->step = 1;
    }

    public function selectPlan(): void
    {
        $this->validate([
            'selectedPlanOptionId' => ['required', 'exists:recruiter_subscription_plan_options,id'],
        ], [
            'selectedPlanOptionId.required' => 'Please select a plan to continue.',
        ]);

        $option = RecruiterSubscriptionPlanOption::with('plan')->findOrFail($this->selectedPlanOptionId);
        $user = auth()->user();

        DB::transaction(function () use ($option, $user) {
            $expiresAt = match ($option->duration_type->value) {
                'monthly' => now()->addMonth(),
                'yearly' => now()->addYear(),
                'lifetime' => null,
                default => now()->addMonth(),
            };

            RecruiterSubscription::create([
                'recruiter_id' => $user->id,
                'recruiter_subscription_plan_id' => $option->plan->id,
                'recruiter_subscription_plan_option_id' => $option->id,
                'plan_name' => $option->plan->name,
                'recruiter_type' => $user->profile?->med_type?->value ?? 'clinics',
                'job_postings_per_month' => $option->job_postings_per_month,
                'is_unlimited_postings' => $option->is_unlimited_postings,
                'status' => $option->price <= 0 ? 'active' : 'pending_payment',
                'starts_at' => now(),
                'expires_at' => $expiresAt,
                'payment_id' => null,
                'assigned_by_admin' => false,
            ]);

            $user->update(['is_profile_completed' => true]);
            $user->profile?->update(['is_profile_completed' => true]);
        });

        $this->redirect('/recruiter/dashboard', navigate: true);
    }

    public function skipPlan(): void
    {
        $user = auth()->user();

        $user->update(['is_profile_completed' => true]);
        $user->profile?->update(['is_profile_completed' => true]);

        $this->redirect('/recruiter/dashboard', navigate: true);
    }

    public function render()
    {
        $user = auth()->user();
        $medType = $user->profile?->med_type?->value ?? 'clinics';

        $plans = RecruiterSubscriptionPlan::with(['options' => fn($q) => $q->where('is_active', true)->orderBy('sort_order')])
            ->where('recruiter_type', $medType)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('livewire.recruiter.onboarding.recruiter-onboarding', [
            'plans' => $plans,
        ]);
    }
}
