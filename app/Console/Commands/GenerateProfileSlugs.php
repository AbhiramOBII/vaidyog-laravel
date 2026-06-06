<?php

namespace App\Console\Commands;

use App\Models\JobSeekerProfile;
use Illuminate\Console\Command;

class GenerateProfileSlugs extends Command
{
    protected $signature = 'profiles:generate-slugs';
    protected $description = 'Generate profile slugs for job seekers who do not have one';

    public function handle(): int
    {
        $profiles = JobSeekerProfile::whereNull('profile_slug')->get();

        $this->info("Found {$profiles->count()} profiles without slugs.");

        foreach ($profiles as $profile) {
            $slug = $profile->generateProfileSlug();
            $profile->update(['profile_slug' => $slug]);
            $this->line("  → {$profile->getFullName()} => {$slug}");
        }

        $this->info('Done.');
        return 0;
    }
}
