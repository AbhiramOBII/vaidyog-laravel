<?php

namespace App\Livewire\Admin\JobSeekers;

use App\Enums\UserStatusEnum;
use App\Models\AdminActionLog;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.admin')]
class JobSeekerShow extends Component
{
    public User $seeker;

    public function mount(string $user): void
    {
        $this->seeker = User::jobSeekers()
            ->with(['jobSeekerProfile.specialty', 'jobSeekerProfile.educations', 'jobSeekerProfile.employments', 'jobSeekerProfile.certifications'])
            ->findOrFail($user);
    }

    public function changeStatus(string $newStatus): void
    {
        $this->seeker->update(['status' => $newStatus]);

        AdminActionLog::create([
            'admin_id' => Auth::guard('admin')->id(),
            'target_type' => 'User',
            'target_id' => $this->seeker->id,
            'action' => 'status_change',
            'notes' => "Changed to {$newStatus}",
        ]);

        $this->seeker->refresh();
        session()->flash('message', 'Status updated to ' . UserStatusEnum::from($newStatus)->label() . '.');
    }

    public function render()
    {
        return view('livewire.admin.job-seekers.job-seeker-show');
    }
}
