<?php

namespace Database\Seeders;

use App\Models\JobSeekerProfile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class JobSeekerSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'jobseeker@vaidyog.com'],
            [
                'name' => 'Rahul Sharma',
                'phone' => '9000000003',
                'password' => Hash::make('JobSeeker@1234'),
                'user_type' => 'user',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        JobSeekerProfile::firstOrCreate(
            ['user_id' => $user->id],
            [
                'first_name' => 'Rahul',
                'last_name' => 'Sharma',
                'date_of_birth' => '1995-06-15',
                'gender' => 'male',
                'phone' => '9000000003',
                'email' => 'jobseeker@vaidyog.com',
                'city' => 'Bengaluru',
                'state' => 'Karnataka',
                'pincode' => '560001',
                'nationality' => 'Indian',
                'designation' => 'Doctor / Physician',
                'subdesignation' => 'General Practitioner',
                'key_skills' => ['Patient Care', 'Diagnosis', 'Clinical Research', 'Emergency Medicine'],
                'about' => 'Experienced general practitioner with 3+ years in clinical settings. Passionate about primary healthcare and preventive medicine.',
                'is_open_to_work' => true,
                'profile_completeness' => 0,
                'category_slug' => 'general',
                'category_name' => 'General',
                'subcategory_name' => 'General',
            ]
        );
    }
}
