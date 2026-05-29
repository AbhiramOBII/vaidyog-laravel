<?php

namespace App\Observers;

use App\Models\MedicalInstitutionProfile;
use App\Services\ReferralCodeService;

class MedicalInstitutionProfileObserver
{
    public function created(MedicalInstitutionProfile $profile): void
    {
        if ($profile->med_type->requiresReferralCode() && !$profile->referral_code) {
            $profile->update([
                'referral_code' => ReferralCodeService::generate(),
            ]);
        }
    }

    public function updating(MedicalInstitutionProfile $profile): void
    {
        if ($profile->isDirty('med_type')) {
            $newType = $profile->med_type;

            if ($newType->requiresReferralCode() && !$profile->referral_code) {
                $profile->referral_code = ReferralCodeService::generate();
            }

            if (!$newType->requiresReferralCode()) {
                $profile->referral_code = null;
            }
        }
    }
}
