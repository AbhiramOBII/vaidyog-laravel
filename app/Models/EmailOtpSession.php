<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailOtpSession extends Model
{
    protected $fillable = [
        'email',
        'otp_code',
        'purpose',
        'expires_at',
        'verified_at',
        'attempts',
        'resend_count',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'verified_at' => 'datetime',
            'attempts' => 'integer',
            'resend_count' => 'integer',
        ];
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public function isVerified(): bool
    {
        return $this->verified_at !== null;
    }
}
