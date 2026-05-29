<?php

namespace App\Services;

use App\Models\MedicalInstitutionProfile;

class ReferralCodeService
{
    public static function generate(): string
    {
        do {
            $code = strtoupper(substr(str_replace(['/', '+', '='], '', base64_encode(random_bytes(8))), 0, 10));
        } while (MedicalInstitutionProfile::where('referral_code', $code)->exists());

        return $code;
    }
}
