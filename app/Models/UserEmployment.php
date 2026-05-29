<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserEmployment extends Model
{
    protected $fillable = [
        'user_id', 'company_name', 'job_title', 'employment_type',
        'is_current', 'joining_date', 'leaving_date',
        'total_experience_months', 'current_salary', 'salary_currency',
        'responsibilities',
    ];

    protected function casts(): array
    {
        return [
            'is_current' => 'boolean',
            'joining_date' => 'date',
            'leaving_date' => 'date',
            'current_salary' => 'decimal:2',
            'total_experience_months' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getDurationLabel(): string
    {
        if (!$this->joining_date) return '';
        $start = $this->joining_date->format('M Y');
        $end = $this->is_current ? 'Present' : ($this->leaving_date?->format('M Y') ?? '');
        $months = $this->total_experience_months ?? 0;
        $years = intdiv($months, 12);
        $rem = $months % 12;
        $duration = $years > 0 ? "{$years} yr" . ($rem > 0 ? " {$rem} mo" : '') : "{$rem} mo";
        return "{$start} – {$end} ({$duration})";
    }
}
