<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserEducation extends Model
{
    protected $table = 'user_educations';

    protected $fillable = [
        'user_id', 'degree', 'university', 'course', 'specialization',
        'course_type', 'course_duration_start', 'course_duration_end',
        'grading_system', 'grade',
    ];

    protected function casts(): array
    {
        return [
            'course_duration_start' => 'integer',
            'course_duration_end' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getDurationLabel(): string
    {
        $start = $this->course_duration_start;
        $end = $this->course_duration_end;
        if (!$start) return '';
        return $end ? "{$start} – {$end}" : "{$start} – Pursuing";
    }

    public function getGradeLabel(): string
    {
        if (!$this->grade) return '';
        return match ($this->grading_system) {
            'percentage' => "{$this->grade}%",
            'cgpa' => "{$this->grade} CGPA",
            default => $this->grade,
        };
    }
}
