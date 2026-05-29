<?php

namespace App\Models;

use App\Enums\PlanDurationTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubscriptionPlanOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'subscription_plan_id',
        'label',
        'duration_type',
        'duration_value',
        'price',
        'applications_per_month',
        'is_unlimited',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'duration_type' => PlanDurationTypeEnum::class,
            'price' => 'decimal:2',
            'is_unlimited' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class, 'subscription_plan_id');
    }
}
