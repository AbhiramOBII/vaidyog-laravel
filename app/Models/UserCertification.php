<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserCertification extends Model
{
    protected $fillable = [
        'user_id', 'name', 'completion_date', 'certification_id',
        'certification_url', 'medical_institute', 'validity_start',
        'validity_end', 'no_expiry',
    ];

    protected function casts(): array
    {
        return [
            'completion_date' => 'date',
            'validity_start' => 'date',
            'validity_end' => 'date',
            'no_expiry' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isValid(): bool
    {
        if ($this->no_expiry) return true;
        if (!$this->validity_end) return true;
        return $this->validity_end->isFuture();
    }

    public function getValidityStatus(): string
    {
        if ($this->no_expiry) return 'no_expiry';
        if (!$this->validity_end) return 'no_expiry';
        return $this->validity_end->isFuture() ? 'valid' : 'expired';
    }
}
