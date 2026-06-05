<?php

namespace App\Livewire\Admin\Roles;

use App\Models\AdminRole;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.admin', ['pageTitle' => 'Role'])]
class RoleForm extends Component
{
    public ?int $roleId = null;
    public string $name = '';
    public string $description = '';
    public array $selectedPermissions = [];

    public function mount(?int $role = null): void
    {
        if ($role) {
            $existing = AdminRole::findOrFail($role);
            $this->roleId = $existing->id;
            $this->name = $existing->name;
            $this->description = $existing->description ?? '';
            $this->selectedPermissions = $existing->permissions ?? [];
        }
    }

    public function togglePermission(string $permission): void
    {
        if (in_array($permission, $this->selectedPermissions)) {
            $this->selectedPermissions = array_values(array_diff($this->selectedPermissions, [$permission]));
        } else {
            $this->selectedPermissions[] = $permission;
        }
    }

    public function selectAll(): void
    {
        $all = [];
        foreach (AdminRole::getAllPermissions() as $group => $perms) {
            $all = array_merge($all, array_keys($perms));
        }
        $this->selectedPermissions = $all;
    }

    public function deselectAll(): void
    {
        $this->selectedPermissions = [];
    }

    public function save(): void
    {
        $this->validate([
            'name' => 'required|max:100|unique:admin_roles,name' . ($this->roleId ? ",{$this->roleId}" : ''),
            'description' => 'nullable|max:255',
            'selectedPermissions' => 'required|array|min:1',
        ], [
            'selectedPermissions.required' => 'Please select at least one permission.',
            'selectedPermissions.min' => 'Please select at least one permission.',
        ]);

        $data = [
            'name' => $this->name,
            'description' => $this->description ?: null,
            'permissions' => $this->selectedPermissions,
        ];

        if ($this->roleId) {
            AdminRole::findOrFail($this->roleId)->update($data);
            session()->flash('success', 'Role updated successfully.');
        } else {
            AdminRole::create($data);
            session()->flash('success', 'Role created successfully.');
        }

        $this->redirect(route('admin.roles.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.roles.role-form', [
            'allPermissions' => AdminRole::getAllPermissions(),
        ]);
    }
}
