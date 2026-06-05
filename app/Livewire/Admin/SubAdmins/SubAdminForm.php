<?php

namespace App\Livewire\Admin\SubAdmins;

use App\Models\Admin;
use App\Models\AdminRole;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.admin', ['pageTitle' => 'Sub-Admin'])]
class SubAdminForm extends Component
{
    public ?string $adminId = null;
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public ?int $admin_role_id = null;
    public bool $is_active = true;

    public function mount(?string $admin = null): void
    {
        if ($admin) {
            $existing = Admin::findOrFail($admin);
            if ($existing->isSuperAdmin()) {
                abort(403, 'Cannot edit super admin via this form.');
            }
            $this->adminId = $existing->id;
            $this->name = $existing->name;
            $this->email = $existing->email;
            $this->admin_role_id = $existing->admin_role_id;
            $this->is_active = $existing->is_active;
        }
    }

    public function save(): void
    {
        $rules = [
            'name' => 'required|max:100',
            'email' => 'required|email|unique:users,email' . ($this->adminId ? ",{$this->adminId}" : ''),
            'admin_role_id' => 'required|exists:admin_roles,id',
            'is_active' => 'boolean',
        ];

        if (!$this->adminId) {
            $rules['password'] = 'required|min:8|confirmed';
        } else {
            $rules['password'] = 'nullable|min:8|confirmed';
        }

        $this->validate($rules);

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'admin_role_id' => $this->admin_role_id,
            'is_active' => $this->is_active,
            'user_type' => 'subadmin',
        ];

        if ($this->password) {
            $data['password'] = $this->password;
        }

        if ($this->adminId) {
            Admin::findOrFail($this->adminId)->update($data);
            session()->flash('success', 'Sub-admin updated successfully.');
        } else {
            Admin::create($data);
            session()->flash('success', 'Sub-admin created successfully.');
        }

        $this->redirect(route('admin.sub-admins.index'), navigate: true);
    }

    public function render()
    {
        $roles = AdminRole::where('is_active', true)->pluck('name', 'id');

        return view('livewire.admin.sub-admins.sub-admin-form', compact('roles'));
    }
}
