<?php

namespace App\Livewire\Admin\SubAdmins;

use App\Models\Admin;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.admin', ['pageTitle' => 'Sub-Admins'])]
class SubAdminIndex extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function toggleActive(string $adminId): void
    {
        $admin = Admin::findOrFail($adminId);
        if ($admin->isSuperAdmin()) {
            session()->flash('error', 'Cannot modify super admin status.');
            return;
        }
        $admin->update(['is_active' => !$admin->is_active]);
    }

    public function delete(string $adminId): void
    {
        $admin = Admin::findOrFail($adminId);
        if ($admin->isSuperAdmin()) {
            session()->flash('error', 'Cannot delete super admin.');
            return;
        }
        $admin->delete();
        session()->flash('success', 'Sub-admin deleted.');
    }

    public function render()
    {
        $subAdmins = Admin::with('role')
            ->where('user_type', 'subadmin')
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%")
                ->orWhere('email', 'like', "%{$this->search}%"))
            ->latest()
            ->paginate(15);

        return view('livewire.admin.sub-admins.sub-admin-index', compact('subAdmins'));
    }
}
