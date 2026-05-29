<?php

namespace Database\Seeders;

use App\Models\MedicalInstitution;
use App\Models\MedicalInstitutionProfile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RecruiterSeeder extends Seeder
{
    public function run(): void
    {
        $user = MedicalInstitution::withoutGlobalScopes()->firstOrCreate(
            ['email' => 'recruiter@vaidyog.com'],
            [
                'name' => 'City Hospital',
                'phone' => '9000000002',
                'password' => Hash::make('Recruiter@1234'),
                'user_type' => 'MedicalInstitution',
                'status' => 'active',
                'auth_provider' => 'email',
                'is_active' => true,
                'is_profile_completed' => false,
                'email_verified_at' => now(),
            ]
        );

        MedicalInstitutionProfile::firstOrCreate(
            ['user_id' => $user->id],
            [
                'institution_name' => 'City Hospital',
                'med_type' => 'clinics',
            ]
        );
    }
}
