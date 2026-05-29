<?php

namespace App\Models;

use App\Enums\ApplicationRankingEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubscriptionPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'ranking',
        'description',
        'is_active',
        'is_recommended',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'ranking' => ApplicationRankingEnum::class,
            'description' => 'array',
            'is_active' => 'boolean',
            'is_recommended' => 'boolean',
        ];
    }

    public function options(): HasMany
    {
        return $this->hasMany(SubscriptionPlanOption::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(UserSubscription::class);
    }

    public function getActiveOptions()
    {
        return $this->options()->where('is_active', true)->orderBy('sort_order')->get();
    }
}
