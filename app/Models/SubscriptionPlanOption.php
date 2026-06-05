<?php

namespace App\Models;

use App\Enums\PlanDurationTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class SubscriptionPlanOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'subscription_plan_id',
        'label',
        'slug',
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

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected static function booted(): void
    {
        static::creating(function (self $option) {
            if (empty($option->slug)) {
                $plan = $option->plan ?? SubscriptionPlan::find($option->subscription_plan_id);
                $base = Str::slug(($plan?->name ?? 'plan') . '-' . $option->label);
                $slug = $base;
                $i = 1;
                while (static::where('slug', $slug)->exists()) {
                    $slug = $base . '-' . $i++;
                }
                $option->slug = $slug;
            }
        });
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class, 'subscription_plan_id');
    }
}
