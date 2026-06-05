<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class AdminRole extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'permissions', 'is_active'];

    protected $casts = [
        'permissions' => 'array',
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $role) {
            if (empty($role->slug)) {
                $role->slug = Str::slug($role->name);
            }
        });
    }

    public function admins(): HasMany
    {
        return $this->hasMany(Admin::class, 'admin_role_id');
    }

    public function hasPermission(string $permission): bool
    {
        $permissions = $this->permissions ?? [];
        return in_array($permission, $permissions);
    }

    public static function getAllPermissions(): array
    {
        return [
            'people' => [
                'job_seekers.view' => 'View Job Seekers',
                'job_seekers.create' => 'Create Job Seekers',
                'job_seekers.edit' => 'Edit Job Seekers',
                'job_seekers.delete' => 'Delete Job Seekers',
                'recruiters.view' => 'View Recruiters',
                'recruiters.create' => 'Create Recruiters',
                'recruiters.edit' => 'Edit Recruiters',
                'recruiters.delete' => 'Delete Recruiters',
            ],
            'jobs' => [
                'jobs.view' => 'View Jobs',
                'jobs.create' => 'Create Jobs',
                'jobs.edit' => 'Edit Jobs',
                'jobs.delete' => 'Delete Jobs',
                'jobs.approve' => 'Approve/Reject Jobs',
            ],
            'applications' => [
                'applications.view' => 'View Applications',
                'applications.delete' => 'Delete Applications',
            ],
            'content' => [
                'blogs.view' => 'View Blogs',
                'blogs.create' => 'Create Blogs',
                'blogs.edit' => 'Edit Blogs',
                'blogs.delete' => 'Delete Blogs',
                'news.view' => 'View News',
                'news.create' => 'Create News',
                'news.edit' => 'Edit News',
                'news.delete' => 'Delete News',
                'events.view' => 'View Events',
                'events.create' => 'Create Events',
                'events.edit' => 'Edit Events',
                'events.delete' => 'Delete Events',
                'faqs.manage' => 'Manage FAQs',
                'specialties.manage' => 'Manage Specialties',
            ],
            'billing' => [
                'plans.view' => 'View Plans',
                'plans.manage' => 'Manage Plans',
                'payments.view' => 'View Payments',
                'subscriptions.view' => 'View Subscriptions',
                'subscriptions.assign' => 'Assign Plans',
            ],
            'support' => [
                'feedbacks.view' => 'View Feedbacks',
                'support_tickets.view' => 'View Support Tickets',
                'support_tickets.manage' => 'Manage Support Tickets',
            ],
            'settings' => [
                'settings.view' => 'View Settings',
                'settings.edit' => 'Edit Settings',
                'sub_admins.view' => 'View Sub-Admins',
                'sub_admins.manage' => 'Manage Sub-Admins',
                'roles.view' => 'View Roles',
                'roles.manage' => 'Manage Roles',
            ],
        ];
    }
}
