<?php

namespace App\Enums;

enum EmploymentTypeEnum: string
{
    case FullTime = 'full_time';
    case PartTime = 'part_time';
    case Contractual = 'contractual';

    public function label(): string
    {
        return match ($this) {
            self::FullTime => 'Full-time',
            self::PartTime => 'Part-time',
            self::Contractual => 'Contractual',
        };
    }
}
