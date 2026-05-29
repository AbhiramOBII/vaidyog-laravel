<?php

namespace App\Models;

use App\Enums\EmploymentTypeEnum;
use App\Enums\JobApprovalStatusEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class JobPosting extends Model
{
    use HasFactory, HasUlids, SoftDeletes;

    protected $table = 'job_postings';

    protected $fillable = [
        'recruiter_id',
        'job_title',
        'slug',
        'job_description',
        'key_skills',
        'employment_type',
        'experience_min',
        'experience_max',
        'location_city',
        'location_state',
        'location_office_address',
        'location_pincode',
        'is_remote',
        'salary_min',
        'salary_max',
        'salary_currency',
        'salary_disclosed',
        'institution_name',
        'contact_name',
        'contact_email',
        'contact_phone',
        'educational_requirements',
        'medical_qualifications',
        'certifications_required',
        'specialties',
        'perks_and_benefits',
        'posting_duration_days',
        'expires_at',
        'number_of_vacancies',
        'category_slug',
        'subcategory_name',
        'specialty_id',
        'admin_approved',
        'approved_at',
        'approved_by_admin_id',
        'rejection_reason',
        'rejected_at',
        'rejected_by_admin_id',
        'is_active',
        'is_featured',
        'featured_at',
        'is_deleted',
        'thumbnail',
    ];

    protected function casts(): array
    {
        return [
            'key_skills' => 'array',
            'educational_requirements' => 'array',
            'medical_qualifications' => 'array',
            'certifications_required' => 'array',
            'specialties' => 'array',
            'perks_and_benefits' => 'array',
            'employment_type' => EmploymentTypeEnum::class,
            'experience_min' => 'decimal:1',
            'experience_max' => 'decimal:1',
            'admin_approved' => 'boolean',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'is_remote' => 'boolean',
            'salary_disclosed' => 'boolean',
            'is_deleted' => 'boolean',
            'approved_at' => 'datetime',
            'rejected_at' => 'datetime',
            'featured_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->where('slug', $value)->first()
            ?? $this->where('id', $value)->first();
    }

    protected static function booted(): void
    {
        static::creating(function (JobPosting $job) {
            if (empty($job->slug)) {
                $base = Str::slug($job->job_title . ' ' . ($job->location_city ?? ''));
                $slug = $base;
                $i = 1;
                while (static::withTrashed()->where('slug', $slug)->exists()) {
                    $slug = $base . '-' . $i++;
                }
                $job->slug = $slug;
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Computed Approval Status
    |--------------------------------------------------------------------------
    */

    public function getApprovalStatus(): JobApprovalStatusEnum
    {
        if ($this->rejection_reason !== null) {
            return JobApprovalStatusEnum::Rejected;
        }

        if ($this->admin_approved) {
            return JobApprovalStatusEnum::Approved;
        }

        return JobApprovalStatusEnum::Pending;
    }

    public function getDisplayStatus(): string
    {
        if ($this->trashed()) return 'Deleted';
        if ($this->rejection_reason !== null) return 'Rejected';
        if (!$this->admin_approved) return 'Pending Approval';
        if (!$this->is_active) return 'Disabled';
        if ($this->expires_at && $this->expires_at->isPast()) return 'Expired';
        return 'Live';
    }

    public function getDisplayStatusColor(): string
    {
        return match ($this->getDisplayStatus()) {
            'Live' => 'green',
            'Pending Approval' => 'amber',
            'Rejected' => 'red',
            'Disabled' => 'neutral',
            'Expired' => 'neutral',
            'Deleted' => 'red',
            default => 'neutral',
        };
    }

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function getThumbnailUrl(): string
    {
        if ($this->thumbnail) {
            return asset('storage/' . $this->thumbnail);
        }

        $recruiterLogo = $this->recruiter?->profile?->logo_path;
        if ($recruiterLogo) {
            return asset('storage/' . $recruiterLogo);
        }

        return asset('images/default-job.jpg');
    }

    public function recruiter(): BelongsTo
    {
        return $this->belongsTo(MedicalInstitution::class, 'recruiter_id')->withoutGlobalScopes();
    }

    public function approvedByAdmin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by_admin_id');
    }

    public function rejectedByAdmin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rejected_by_admin_id');
    }

    public function binRecord(): HasOne
    {
        return $this->hasOne(JobBin::class, 'job_id');
    }

    public function specialty(): BelongsTo
    {
        return $this->belongsTo(Specialty::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(JobApplication::class, 'job_id');
    }

    // public function interviews(): HasMany { return $this->hasMany(Interview::class, 'job_id'); }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('admin_approved', true);
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('admin_approved', false)->whereNull('rejection_reason');
    }

    public function scopeRejected(Builder $query): Builder
    {
        return $query->whereNotNull('rejection_reason');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function scopeNotExpired(Builder $query): Builder
    {
        return $query->where(function ($q) {
            $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
        });
    }

    public function scopeExpired(Builder $query): Builder
    {
        return $query->whereNotNull('expires_at')->where('expires_at', '<=', now());
    }

    public function scopeForRecruiter(Builder $query, string $id): Builder
    {
        return $query->where('recruiter_id', $id);
    }

    public function scopePubliclyVisible(Builder $query): Builder
    {
        return $query->approved()->active()->notExpired();
    }

    public function scopeExpiringSoon(Builder $query, int $days = 7): Builder
    {
        return $query->whereNotNull('expires_at')
            ->where('expires_at', '>', now())
            ->where('expires_at', '<=', now()->addDays($days));
    }
}
