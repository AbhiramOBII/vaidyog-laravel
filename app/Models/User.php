<?php

namespace App\Models;

use App\Enums\AuthProviderEnum;
use App\Enums\UserStatusEnum;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasUlids, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
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

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
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

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function jobSeekerProfile(): HasOne
    {
        return $this->hasOne(JobSeekerProfile::class);
    }

    public function profile(): HasOne
    {
        return $this->jobSeekerProfile();
    }

    public function applications(): HasMany
    {
        return $this->hasMany(JobApplication::class, 'applicant_id');
    }

    public function savedJobs(): HasMany
    {
        return $this->hasMany(SavedJob::class);
    }

    public function languages(): HasMany
    {
        return $this->hasMany(UserLanguage::class);
    }

    public function certifications(): HasMany
    {
        return $this->hasMany(UserCertification::class);
    }

    public function educations(): HasMany
    {
        return $this->hasMany(UserEducation::class);
    }

    public function employments(): HasMany
    {
        return $this->hasMany(UserEmployment::class);
    }

    public function projects(): HasMany
    {
        return $this->hasMany(UserProject::class);
    }

    public function publications(): HasMany
    {
        return $this->hasMany(UserPublication::class);
    }

    public function presentations(): HasMany
    {
        return $this->hasMany(UserPresentation::class);
    }

    public function researches(): HasMany
    {
        return $this->hasMany(UserResearch::class);
    }

    public function honoursAwards(): HasMany
    {
        return $this->hasMany(UserHonoursAward::class);
    }

    public function affiliations(): HasMany
    {
        return $this->hasMany(UserAffiliation::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(UserSubscription::class);
    }

    public function activeSubscription(): ?UserSubscription
    {
        return $this->subscriptions()->active()->latest('starts_at')->first();
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeJobSeekers(Builder $query): Builder
    {
        return $query->where('user_type', 'user');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', UserStatusEnum::Active);
    }

    public function scopeByStatus(Builder $query, UserStatusEnum $status): Builder
    {
        return $query->where('status', $status);
    }
}
