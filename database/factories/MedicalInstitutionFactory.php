<?php

namespace Database\Factories;

use App\Models\MedicalInstitution;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends Factory<MedicalInstitution>
 */
class MedicalInstitutionFactory extends Factory
{
    protected $model = MedicalInstitution::class;

    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'email' => fake()->unique()->companyEmail(),
            'phone' => fake()->numerify('##########'),
            'password' => Hash::make('password'),
            'user_type' => 'recruiter',
            'status' => 'active',
            'is_active' => true,
            'email_verified_at' => now(),
        ];
    }
}
