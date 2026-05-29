<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        Admin::firstOrCreate(
            ['email' => 'admin@vaidyog.com'],
            [
                'name' => 'Super Admin',
                'phone' => '9000000001',
                'password' => 'Admin@1234',
                'user_type' => 'admin',
                'is_active' => true,
            ]
        );
    }
}
