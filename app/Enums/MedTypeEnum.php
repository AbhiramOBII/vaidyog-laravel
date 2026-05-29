<?php

namespace App\Enums;

enum MedTypeEnum: string
{
    case Clinics = 'clinics';
    case SmallHospital = 'small_hospital';
    case LargerHospital = 'larger_hospital';
    case Enterprise = 'enterprise';
    case EnterpriseBranch = 'enterprise_branch';

    public function label(): string
    {
        return match ($this) {
            self::Clinics => 'Clinic',
            self::SmallHospital => 'Small Hospital',
            self::LargerHospital => 'Larger Hospital',
            self::Enterprise => 'Enterprise',
            self::EnterpriseBranch => 'Enterprise Branch',
        };
    }

    public function requiresReferralCode(): bool
    {
        return in_array($this, [self::LargerHospital, self::Enterprise, self::EnterpriseBranch]);
    }
}
