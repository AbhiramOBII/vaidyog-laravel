<?php

namespace App\Observers;

use App\Models\UserEmployment;
use Carbon\Carbon;

class UserEmploymentObserver
{
    public function saving(UserEmployment $employment): void
    {
        if ($employment->is_current) {
            $employment->leaving_date = null;
        }

        if ($employment->joining_date) {
            $end = $employment->is_current ? now() : ($employment->leaving_date ?? now());
            $employment->total_experience_months = Carbon::parse($employment->joining_date)->diffInMonths($end);
        }
    }
}
