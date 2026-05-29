<?php

namespace App\Models;

use App\Enums\FeaturedPurchaseStatusEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FeaturedJobPurchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'recruiter_id',
        'job_id',
        'featured_job_plan_id',
        'price_paid',
        'payment_id',
        'featured_from',
        'featured_until',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'price_paid' => 'decimal:2',
            'status' => FeaturedPurchaseStatusEnum::class,
            'featured_from' => 'datetime',
            'featured_until' => 'datetime',
        ];
    }

    public function recruiter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recruiter_id');
    }

    public function job(): BelongsTo
    {
        return $this->belongsTo(JobPosting::class, 'job_id');
    }

    public function featuredJobPlan(): BelongsTo
    {
        return $this->belongsTo(FeaturedJobPlan::class);
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active')->where('featured_until', '>', now());
    }
}
