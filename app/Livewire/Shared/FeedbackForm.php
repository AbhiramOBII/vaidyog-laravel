<?php

namespace App\Livewire\Shared;

use App\Models\Feedback;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class FeedbackForm extends Component
{
    public string $feedback = '';

    public function submit(): void
    {
        $this->validate([
            'feedback' => 'required|string|max:2000',
        ]);

        $user = Auth::user();

        Feedback::create([
            'user_id' => $user->id,
            'feedback' => $this->feedback,
            'name' => $user->name,
            'user_type' => $user->user_type === 'MedicalInstitution' ? 'recruiter' : 'job_seeker',
            'read_status' => false,
        ]);

        $this->feedback = '';
        session()->flash('success', 'Thank you for your feedback!');
    }

    public function render()
    {
        return view('livewire.shared.feedback-form');
    }
}
