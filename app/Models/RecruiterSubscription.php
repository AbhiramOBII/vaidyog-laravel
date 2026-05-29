<?php

namespace App\Models;

use App\Enums\MedTypeEnum;
use App\Enums\SubscriptionStatusEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecruiterSubscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'recruiter_id',
        'recruiter_subscription_plan_id',
        'recruiter_subscription_plan_option_id',
        'plan_name',
        'recruiter_type',
        'job_postings_per_month',
        'is_unlimited_postings',
        'status',
        'starts_at',
        'expires_at',
        'payment_id',
        'assigned_by_admin',
    ];

    protected function casts(): array
    {
        return [
            'recruiter_type' => MedTypeEnum::class,
            'is_unlimited_postings' => 'boolean',
            'status' => SubscriptionStatusEnum::class,
            'starts_at' => 'datetime',
            'expires_at' => 'datetime',
            'assigned_by_admin' => 'boolean',
        ];
    }

    public function recruiter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recruiter_id');
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(RecruiterSubscriptionPlan::class, 'recruiter_subscription_plan_id');
    }

    public function option(): BelongsTo
    {
        return $this->belongsTo(RecruiterSubscriptionPlanOption::class, 'recruiter_subscription_plan_option_id');
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active')
            ->where(function ($q) {
                $q->where('expires_at', '>', now())->orWhereNull('expires_at');
            });
    }

    public function isActive(): bool
    {
        return $this->status === SubscriptionStatusEnum::Active
            && ($this->expires_at === null || $this->expires_at->isFuture());
    }
}
