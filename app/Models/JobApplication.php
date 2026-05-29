<?php

namespace App\Models;

use App\Enums\ApplicationRankingEnum;
use App\Enums\ApplicationStatusEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobApplication extends Model
{
    use HasFactory, HasUlids, SoftDeletes;

    protected $fillable = [
        'job_id',
        'applicant_id',
        'recruiter_id',
        'status',
        'ranking',
        'matching_skills',
        'cover_note',
        'resume_path',
        'status_dates',
        'recruiter_notes',
        'applied_at',
    ];

    protected function casts(): array
    {
        return [
            'matching_skills' => 'array',
            'status_dates' => 'array',
            'status' => ApplicationStatusEnum::class,
            'ranking' => ApplicationRankingEnum::class,
            'applied_at' => 'datetime',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function job(): BelongsTo
    {
        return $this->belongsTo(JobPosting::class, 'job_id');
    }

    public function applicant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'applicant_id');
    }

    public function recruiter(): BelongsTo
    {
        return $this->belongsTo(MedicalInstitution::class, 'recruiter_id')->withoutGlobalScopes();
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeByStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }

    public function scopeByRanking(Builder $query, string $ranking): Builder
    {
        return $query->where('ranking', $ranking);
    }

    public function scopeForJob(Builder $query, string $jobId): Builder
    {
        return $query->where('job_id', $jobId);
    }

    public function scopeForRecruiter(Builder $query, string $recruiterId): Builder
    {
        return $query->where('recruiter_id', $recruiterId);
    }

    public function scopeForApplicant(Builder $query, string $applicantId): Builder
    {
        return $query->where('applicant_id', $applicantId);
    }

    public function scopeOrderedByRanking(Builder $query): Builder
    {
        return $query->orderByRaw("FIELD(ranking, 'A', 'B', 'C', 'D')");
    }

    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    */

    public function updateStatus(string $status): void
    {
        $dates = $this->status_dates ?? [];
        $dates[$status] = now()->toIso8601String();

        $this->update([
            'status' => $status,
            'status_dates' => $dates,
        ]);
    }

    public function getStatusHistory(): array
    {
        $dates = $this->status_dates ?? [];
        asort($dates);
        return $dates;
    }

    public function isOwnedByRecruiter(string $recruiterId): bool
    {
        return $this->recruiter_id === $recruiterId;
    }
}
