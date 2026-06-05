<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use HasUlids;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'user_type',
        'auth_id',
        'is_active',
        'admin_role_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope('admin', function (Builder $builder) {
            $builder->whereIn('user_type', ['admin', 'subadmin']);
        });

        static::creating(function ($model) {
            if (!in_array($model->user_type, ['admin', 'subadmin'])) {
                $model->user_type = 'admin';
            }
        });
    }

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'is_active' => 'boolean',
            'last_login_at' => 'datetime',
        ];
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(AdminRole::class, 'admin_role_id');
    }

    public function isSuperAdmin(): bool
    {
        return $this->user_type === 'admin';
    }

    public function isSubAdmin(): bool
    {
        return $this->user_type === 'subadmin';
    }

    public function hasPermission(string $permission): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        $role = $this->role;
        if (!$role || !$role->is_active) {
            return false;
        }

        return $role->hasPermission($permission);
    }

    public function hasAnyPermission(array $permissions): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }

        return false;
    }

    public function canLogin(): bool
    {
        return $this->is_active === true;
    }
}
