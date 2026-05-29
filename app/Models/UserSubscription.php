<?php

namespace App\Models;

use App\Enums\ApplicationRankingEnum;
use App\Enums\SubscriptionStatusEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSubscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'subscription_plan_id',
        'subscription_plan_option_id',
        'plan_name',
        'ranking',
        'applications_per_month',
        'is_unlimited',
        'status',
        'starts_at',
        'expires_at',
        'payment_id',
        'assigned_by_admin',
    ];

    protected function casts(): array
    {
        return [
            'ranking' => ApplicationRankingEnum::class,
            'is_unlimited' => 'boolean',
            'status' => SubscriptionStatusEnum::class,
            'starts_at' => 'datetime',
            'expires_at' => 'datetime',
            'assigned_by_admin' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class, 'subscription_plan_id');
    }

    public function option(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlanOption::class, 'subscription_plan_option_id');
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
