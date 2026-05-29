<?php

namespace App\Livewire\Frontend\Auth;

use App\Enums\MedTypeEnum;
use App\Models\MedicalInstitution;
use App\Models\MedicalInstitutionProfile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.guest')]
class RecruiterRegistration extends Component
{
    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public string $med_type = '';
    public string $password = '';
    public string $password_confirmation = '';

    public string $error = '';

    public function register(): void
    {
        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'digits:10', 'unique:users,phone'],
            'med_type' => ['required', Rule::in(array_column(MedTypeEnum::cases(), 'value'))],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'email.unique' => 'An account with this email already exists.',
            'phone.unique' => 'An account with this phone number already exists.',
        ]);

        DB::transaction(function () {
            $user = MedicalInstitution::withoutGlobalScopes()->create([
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'password' => Hash::make($this->password),
                'user_type' => 'MedicalInstitution',
                'status' => 'active',
                'auth_provider' => 'email',
                'is_active' => true,
                'is_profile_completed' => false,
            ]);

            MedicalInstitutionProfile::create([
                'user_id' => $user->id,
                'institution_name' => $this->name,
                'med_type' => $this->med_type,
            ]);

            auth()->login($user);
        });

        $this->redirect('/recruiter/onboarding', navigate: true);
    }

    public function render()
    {
        return view('livewire.frontend.auth.recruiter-registration', [
            'medTypes' => MedTypeEnum::cases(),
        ]);
    }
}
