<?php

namespace App\Livewire\Admin\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Component;

#[Layout('components.layouts.admin-guest')]
class AdminLogin extends Component
{
    #[Rule('required|email|max:255')]
    public string $email = '';

    #[Rule('required|min:6')]
    public string $password = '';

    public bool $rememberMe = false;

    public string $errorMessage = '';

    public function login(): void
    {
        $this->errorMessage = '';

        $key = 'admin-login:' . request()->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            $this->errorMessage = "Too many attempts. Try again in {$seconds} seconds.";
            return;
        }

        $this->validate();

        if (Auth::guard('admin')->attempt(
            ['email' => $this->email, 'password' => $this->password, 'is_active' => true],
            $this->rememberMe
        )) {
            RateLimiter::clear($key);

            Auth::guard('admin')->user()->update(['last_login_at' => now()]);

            session()->regenerate();

            $this->redirect(route('admin.dashboard'));
        } else {
            RateLimiter::hit($key, 60);
            $this->errorMessage = 'Invalid credentials or your account is inactive.';
        }
    }

    public function resetError(): void
    {
        $this->errorMessage = '';
    }

    public function render()
    {
        return view('livewire.admin.auth.admin-login');
    }
}
