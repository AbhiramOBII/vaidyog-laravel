<?php

namespace App\Livewire\JobSeeker\Profile\Recognitions;

use App\Models\UserAffiliation;
use Livewire\Component;

class AffiliationManager extends Component
{
    public bool $showForm = false;
    public ?int $editingId = null;
    public string $organization_name = '';
    public string $role = '';
    public ?string $member_since = null;
    public ?string $member_until = null;
    public bool $is_current = false;

    protected function rules(): array
    {
        return ['organization_name' => 'required|max:150', 'role' => 'nullable|max:100', 'member_since' => 'nullable|date', 'member_until' => 'nullable|date|after:member_since'];
    }

    public function openForm(?int $id = null): void
    {
        if ($id) {
            $item = UserAffiliation::where('user_id', auth()->id())->findOrFail($id);
            $this->editingId = $id; $this->organization_name = $item->organization_name; $this->role = $item->role ?? ''; $this->member_since = $item->member_since?->format('Y-m-d'); $this->member_until = $item->member_until?->format('Y-m-d'); $this->is_current = $item->is_current;
        } else { $this->resetForm(); }
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->validate();
        UserAffiliation::updateOrCreate(
            ['id' => $this->editingId, 'user_id' => auth()->id()],
            ['user_id' => auth()->id(), 'organization_name' => $this->organization_name, 'role' => $this->role ?: null, 'member_since' => $this->member_since ?: null, 'member_until' => $this->is_current ? null : ($this->member_until ?: null), 'is_current' => $this->is_current]
        );
        $this->resetForm(); $this->showForm = false;
    }

    public function delete(int $id): void { UserAffiliation::where('user_id', auth()->id())->where('id', $id)->delete(); }
    public function cancel(): void { $this->resetForm(); $this->showForm = false; }

    private function resetForm(): void
    {
        $this->editingId = null; $this->organization_name = ''; $this->role = ''; $this->member_since = null; $this->member_until = null; $this->is_current = false;
        $this->resetValidation();
    }

    public function render()
    {
        $items = UserAffiliation::where('user_id', auth()->id())->latest()->get();
        return view('livewire.job-seeker.profile.recognitions.affiliation-manager', compact('items'));
    }
}
