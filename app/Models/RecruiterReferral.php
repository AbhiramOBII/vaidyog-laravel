<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecruiterReferral extends Model
{
    protected $fillable = [
        'recruiter_id',
        'referred_user_id',
        'referral_code',
        'status',
    ];

    public function recruiter(): BelongsTo
    {
        return $this->belongsTo(MedicalInstitution::class, 'recruiter_id');
    }

    public function referredUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referred_user_id');
    }
}
