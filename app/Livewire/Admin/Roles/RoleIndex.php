<?php

namespace App\Livewire\Admin\Roles;

use App\Models\AdminRole;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.admin', ['pageTitle' => 'Roles'])]
class RoleIndex extends Component
{
    public string $search = '';

    public function delete(int $roleId): void
    {
        $role = AdminRole::findOrFail($roleId);

        if ($role->admins()->count() > 0) {
            session()->flash('error', 'Cannot delete role with assigned sub-admins.');
            return;
        }

        $role->delete();
        session()->flash('success', 'Role deleted successfully.');
    }

    public function render()
    {
        $roles = AdminRole::query()
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->withCount('admins')
            ->latest()
            ->get();

        return view('livewire.admin.roles.role-index', compact('roles'));
    }
}
