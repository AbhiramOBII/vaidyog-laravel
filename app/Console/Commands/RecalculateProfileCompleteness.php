<?php

namespace App\Console\Commands;

use App\Models\JobSeekerProfile;
use App\Services\Profile\ProfileCompletenessService;
use Illuminate\Console\Command;

class RecalculateProfileCompleteness extends Command
{
    protected $signature = 'profile:recalculate-completeness';
    protected $description = 'Recalculate profile completeness for all job seeker profiles';

    public function handle(ProfileCompletenessService $service): int
    {
        $count = 0;
        JobSeekerProfile::chunk(100, function ($profiles) use ($service, &$count) {
            foreach ($profiles as $profile) {
                $score = $service->calculate($profile);
                $profile->updateQuietly(['profile_completeness' => $score]);
                $count++;
            }
        });

        $this->info("Recalculated completeness for {$count} profiles.");
        return self::SUCCESS;
    }
}
