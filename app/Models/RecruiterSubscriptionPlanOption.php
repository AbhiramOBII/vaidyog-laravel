<?php

namespace App\Models;

use App\Enums\PlanDurationTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecruiterSubscriptionPlanOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'recruiter_subscription_plan_id',
        'label',
        'duration_type',
        'duration_value',
        'price',
        'job_postings_per_month',
        'is_unlimited_postings',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'duration_type' => PlanDurationTypeEnum::class,
            'price' => 'decimal:2',
            'is_unlimited_postings' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(RecruiterSubscriptionPlan::class, 'recruiter_subscription_plan_id');
    }
}
