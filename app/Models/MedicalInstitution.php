<?php

namespace App\Models;

use App\Enums\AuthProviderEnum;
use App\Enums\MedTypeEnum;
use App\Enums\UserStatusEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class MedicalInstitution extends Authenticatable
{
    use HasApiTokens, HasFactory, HasUlids, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'user_type',
        'status',
        'auth_provider',
        'google_id',
        'auth_id',
        'phone_verified_at',
        'email_verified_at',
        'is_active',
        'is_profile_completed',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'status' => UserStatusEnum::class,
            'auth_provider' => AuthProviderEnum::class,
            'phone_verified_at' => 'datetime',
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'is_active' => 'boolean',
            'is_profile_completed' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::addGlobalScope('medical_institution', function (Builder $builder) {
            $builder->where('user_type', 'MedicalInstitution');
        });

        static::creating(function ($model) {
            $model->user_type = 'MedicalInstitution';
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function profile(): HasOne
    {
        return $this->hasOne(MedicalInstitutionProfile::class, 'user_id');
    }

    public function referrals(): HasMany
    {
        return $this->hasMany(RecruiterReferral::class, 'recruiter_id');
    }

    public function jobPostings(): HasMany
    {
        return $this->hasMany(JobPosting::class, 'recruiter_id');
    }

    public function recruiterSubscriptions(): HasMany
    {
        return $this->hasMany(RecruiterSubscription::class, 'recruiter_id');
    }

    public function activeRecruiterSubscription(): ?RecruiterSubscription
    {
        return $this->recruiterSubscriptions()->active()->latest('starts_at')->first();
    }

    public function featuredJobPurchases(): HasMany
    {
        return $this->hasMany(FeaturedJobPurchase::class, 'recruiter_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'user_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeByMedType(Builder $query, string|MedTypeEnum $type): Builder
    {
        $value = $type instanceof MedTypeEnum ? $type->value : $type;
        return $query->whereHas('profile', fn ($q) => $q->where('med_type', $value));
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', UserStatusEnum::Active);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->whereHas('profile', fn ($q) => $q->where('is_featured', true));
    }

    public function scopeByStatus(Builder $query, UserStatusEnum|string $status): Builder
    {
        $value = $status instanceof UserStatusEnum ? $status->value : $status;
        return $query->where('status', $value);
    }
}
