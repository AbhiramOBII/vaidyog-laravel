<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            JobTaxonomySeeder::class,
            SubscriptionPlanSeeder::class,
            DesignationSeeder::class,
            SpecialtySeeder::class,
            RecruiterSeeder::class,
            JobSeekerSeeder::class,
            JobPostingSeeder::class,
            FaqSeeder::class,
            BlogSeeder::class,
        ]);
    }
}
