<?php

namespace App\Livewire\Admin\Recruiters;

use App\Enums\UserStatusEnum;
use App\Models\AdminActionLog;
use App\Models\MedicalInstitution;
use App\Models\RecruiterReferral;
use App\Services\ReferralCodeService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.admin')]
class RecruiterShow extends Component
{
    public MedicalInstitution $recruiter;

    public function mount(string $user): void
    {
        $this->recruiter = MedicalInstitution::with(['profile', 'referrals.referredUser'])->findOrFail($user);
    }

    public function toggleFeatured(): void
    {
        $profile = $this->recruiter->profile;
        $newState = !$profile->is_featured;
        $profile->update([
            'is_featured' => $newState,
            'featured_at' => $newState ? now() : null,
        ]);

        AdminActionLog::create([
            'admin_id' => Auth::guard('admin')->id(),
            'target_type' => 'MedicalInstitution',
            'target_id' => $this->recruiter->id,
            'action' => $newState ? 'featured' : 'unfeatured',
            'notes' => $newState ? 'Marked as featured.' : 'Featured status removed.',
        ]);

        $this->recruiter->refresh();
        session()->flash('message', $newState ? 'Recruiter marked as featured.' : 'Featured status removed.');
    }

    public function changeStatus(string $newStatus): void
    {
        $this->recruiter->update(['status' => $newStatus]);

        AdminActionLog::create([
            'admin_id' => Auth::guard('admin')->id(),
            'target_type' => 'MedicalInstitution',
            'target_id' => $this->recruiter->id,
            'action' => 'status_change',
            'notes' => "Changed to {$newStatus}",
        ]);

        $this->recruiter->refresh();
        session()->flash('message', 'Status updated to ' . UserStatusEnum::from($newStatus)->label() . '.');
    }

    public function regenerateReferralCode(): void
    {
        $profile = $this->recruiter->profile;
        if (!$profile) return;

        $profile->update([
            'referral_code' => ReferralCodeService::generate(),
        ]);

        AdminActionLog::create([
            'admin_id' => Auth::guard('admin')->id(),
            'target_type' => 'MedicalInstitution',
            'target_id' => $this->recruiter->id,
            'action' => 'referral_regenerated',
            'notes' => 'Referral code regenerated.',
        ]);

        $this->recruiter->refresh();
        session()->flash('message', 'Referral code regenerated.');
    }

    public function render()
    {
        return view('livewire.admin.recruiters.recruiter-show');
    }
}
