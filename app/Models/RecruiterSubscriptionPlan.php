<?php

namespace App\Models;

use App\Enums\MedTypeEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RecruiterSubscriptionPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'recruiter_type',
        'description',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'recruiter_type' => MedTypeEnum::class,
            'description' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function options(): HasMany
    {
        return $this->hasMany(RecruiterSubscriptionPlanOption::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(RecruiterSubscription::class);
    }

    public function scopeForRecruiterType(Builder $query, string $type): Builder
    {
        return $query->where('recruiter_type', $type);
    }

    public function getActiveOptions()
    {
        return $this->options()->where('is_active', true)->orderBy('sort_order')->get();
    }
}
