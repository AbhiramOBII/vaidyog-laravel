<?php

namespace App\Livewire;

use App\Models\NewsletterSubscriber;
use Livewire\Component;

class NewsletterSubscribe extends Component
{
    public string $email = '';
    public string $message = '';
    public bool $success = false;

    public function subscribe(): void
    {
        $this->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);

        $existing = NewsletterSubscriber::where('email', $this->email)->first();

        if ($existing) {
            if ($existing->is_active) {
                $this->message = 'You are already subscribed!';
                $this->success = true;
            } else {
                $existing->update([
                    'is_active' => true,
                    'unsubscribed_at' => null,
                    'subscribed_at' => now(),
                    'ip_address' => request()->ip(),
                ]);
                $this->message = 'Welcome back! You have been re-subscribed.';
                $this->success = true;
            }
        } else {
            NewsletterSubscriber::create([
                'email' => $this->email,
                'ip_address' => request()->ip(),
            ]);
            $this->message = 'Thank you for subscribing!';
            $this->success = true;
        }

        $this->email = '';
    }

    public function render()
    {
        return view('livewire.newsletter-subscribe');
    }
}
