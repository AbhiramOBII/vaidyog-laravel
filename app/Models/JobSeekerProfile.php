<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class JobSeekerProfile extends Model
{
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'category_slug',
        'category_name',
        'subcategory_name',
        'specialty_id',
        'date_of_birth',
        'gender',
        'email',
        'phone',
        'city',
        'state',
        'pincode',
        'nationality',
        'designation',
        'subdesignation',
        'about',
        'experience_years',
        'current_employer',
        'highest_qualification',
        'resume_path',
        'key_skills',
        'active_plan_snapshot',
        'profile_completeness',
        'is_open_to_work',
        'profile_photo_path',
        'created_by_admin_id',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'experience_years' => 'decimal:1',
            'key_skills' => 'array',
            'active_plan_snapshot' => 'array',
            'profile_completeness' => 'integer',
            'is_open_to_work' => 'boolean',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function createdByAdmin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_admin_id');
    }

    public function specialty(): BelongsTo
    {
        return $this->belongsTo(Specialty::class);
    }

    public function languages(): HasMany
    {
        return $this->hasMany(UserLanguage::class, 'user_id', 'user_id');
    }

    public function certifications(): HasMany
    {
        return $this->hasMany(UserCertification::class, 'user_id', 'user_id');
    }

    public function educations(): HasMany
    {
        return $this->hasMany(UserEducation::class, 'user_id', 'user_id');
    }

    public function employments(): HasMany
    {
        return $this->hasMany(UserEmployment::class, 'user_id', 'user_id');
    }

    public function projects(): HasMany
    {
        return $this->hasMany(UserProject::class, 'user_id', 'user_id');
    }

    public function publications(): HasMany
    {
        return $this->hasMany(UserPublication::class, 'user_id', 'user_id');
    }

    public function presentations(): HasMany
    {
        return $this->hasMany(UserPresentation::class, 'user_id', 'user_id');
    }

    public function researches(): HasMany
    {
        return $this->hasMany(UserResearch::class, 'user_id', 'user_id');
    }

    public function honoursAwards(): HasMany
    {
        return $this->hasMany(UserHonoursAward::class, 'user_id', 'user_id');
    }

    public function affiliations(): HasMany
    {
        return $this->hasMany(UserAffiliation::class, 'user_id', 'user_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */

    public function getFullName(): string
    {
        $name = trim(($this->first_name ?? '') . ' ' . ($this->last_name ?? ''));
        return $name ?: ($this->user?->name ?? '');
    }

    public function getProfilePictureUrl(): string
    {
        if ($this->profile_photo_path) {
            return Storage::url($this->profile_photo_path);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->getFullName()) . '&background=4ab098&color=fff&size=120';
    }

    public function getResumeUrl(): ?string
    {
        return $this->resume_path ? Storage::url($this->resume_path) : null;
    }

    public function getTotalExperience(): string
    {
        $totalMonths = $this->employments()->sum('total_experience_months');
        if ($totalMonths === 0) return 'Fresher';
        $years = intdiv($totalMonths, 12);
        $months = $totalMonths % 12;
        if ($years > 0 && $months > 0) return "{$years} yr {$months} mo";
        if ($years > 0) return "{$years} yr";
        return "{$months} mo";
    }

    public function getCompletenessColor(): string
    {
        return match (true) {
            $this->profile_completeness >= 86 => 'green',
            $this->profile_completeness >= 61 => 'teal',
            $this->profile_completeness >= 31 => 'amber',
            default => 'red',
        };
    }

    public function getCompletenessLabel(): string
    {
        return match (true) {
            $this->profile_completeness >= 86 => 'Profile complete',
            $this->profile_completeness >= 61 => 'Looking good!',
            $this->profile_completeness >= 31 => 'Building your profile',
            default => 'Just getting started',
        };
    }
}
