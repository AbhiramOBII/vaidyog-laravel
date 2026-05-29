<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobBin extends Model
{
    use HasUlids;

    protected $table = 'job_bins';

    public $timestamps = false;

    protected $fillable = [
        'job_id',
        'deleted_by_type',
        'deleted_by_id',
        'original_data',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'original_data' => 'array',
            'created_at' => 'datetime',
        ];
    }

    public function job(): BelongsTo
    {
        return $this->belongsTo(JobPosting::class, 'job_id')->withTrashed();
    }

    public function deletedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by_id');
    }
}
