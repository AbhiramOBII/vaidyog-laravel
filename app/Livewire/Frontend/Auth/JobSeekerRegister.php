<?php

namespace App\Livewire\Frontend\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.guest')]
class JobSeekerRegister extends Component
{
    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $error = '';

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|digits:10|unique:users,phone',
            'password' => 'required|string|min:8|confirmed',
        ];
    }

    protected function messages(): array
    {
        return [
            'email.unique' => 'An account with this email already exists.',
            'phone.unique' => 'An account with this phone number already exists.',
        ];
    }

    public function register(): void
    {
        $this->validate();

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'password' => Hash::make($this->password),
            'user_type' => 'user',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        Auth::login($user);
        $this->redirect(route('jobseeker.dashboard'), navigate: true);
    }

    public function render()
    {
        return view('livewire.frontend.auth.job-seeker-register');
    }
}
