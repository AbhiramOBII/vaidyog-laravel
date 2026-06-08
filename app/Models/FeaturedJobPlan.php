<?php

namespace App\Models;

use App\Enums\MedTypeEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FeaturedJobPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'recruiter_type',
        'name',
        'price_per_post',
        'featured_duration_days',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'recruiter_type' => MedTypeEnum::class,
            'price_per_post' => 'decimal:2',
            'featured_duration_days' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function purchases(): HasMany
    {
        return $this->hasMany(FeaturedJobPurchase::class);
    }

    public function scopeForRecruiterType(Builder $query, ?string $type): Builder
    {
        return $query->where(function ($q) use ($type) {
            $q->where('recruiter_type', $type)->orWhereNull('recruiter_type');
        });
    }
}
