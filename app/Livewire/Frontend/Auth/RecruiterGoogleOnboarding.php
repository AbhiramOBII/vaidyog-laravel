<?php

namespace App\Livewire\Frontend\Auth;

use App\Enums\MedTypeEnum;
use App\Models\MedicalInstitution;
use App\Models\MedicalInstitutionProfile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.guest')]
class RecruiterGoogleOnboarding extends Component
{
    public string $email = '';
    public string $name = '';
    public string $institutionName = '';
    public string $medType = '';
    public string $contactPersonName = '';
    public string $phone = '';

    public function mount(): void
    {
        $data = session('google_recruiter_data');
        if (!$data) {
            $this->redirect(route('recruiter.register'));
            return;
        }

        $this->email = $data['email'] ?? '';
        $this->name = $data['name'] ?? '';
        $this->contactPersonName = $data['name'] ?? '';
    }

    public function rules(): array
    {
        return [
            'institutionName' => ['required', 'string', 'max:255'],
            'medType' => ['required', Rule::in(array_column(MedTypeEnum::cases(), 'value'))],
            'contactPersonName' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'digits_between:10,15'],
        ];
    }

    public function complete(): void
    {
        $this->validate();

        $data = session('google_recruiter_data');
        if (!$data) {
            $this->redirect(route('recruiter.register'));
            return;
        }

        DB::transaction(function () use ($data) {
            $user = MedicalInstitution::withoutGlobalScopes()->create([
                'name' => $this->institutionName,
                'email' => $data['email'],
                'phone' => $this->phone ?: null,
                'password' => null,
                'user_type' => 'MedicalInstitution',
                'status' => 'pending_verification',
                'auth_provider' => 'google',
                'google_id' => $data['google_id'],
                'is_active' => true,
                'is_profile_completed' => false,
                'email_verified_at' => now(),
            ]);

            MedicalInstitutionProfile::create([
                'user_id' => $user->id,
                'institution_name' => $this->institutionName,
                'med_type' => $this->medType,
                'contact_person_name' => $this->contactPersonName,
                'contact_person_phone' => $this->phone ?: null,
            ]);

            Auth::login($user);
        });

        session()->forget('google_recruiter_data');
        $this->redirect('/recruiter/dashboard');
    }

    public function render()
    {
        return view('livewire.frontend.auth.recruiter-google-onboarding', [
            'medTypes' => MedTypeEnum::cases(),
        ]);
    }
}
