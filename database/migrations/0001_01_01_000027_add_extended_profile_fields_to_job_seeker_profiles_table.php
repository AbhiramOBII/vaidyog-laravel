<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('job_seeker_profiles', function (Blueprint $table) {
            $table->string('first_name')->nullable()->after('user_id');
            $table->string('last_name')->nullable()->after('first_name');
            $table->string('email')->nullable()->after('gender');
            $table->string('phone')->nullable()->after('email');
            $table->string('nationality')->default('Indian')->after('pincode');
            $table->string('designation')->nullable()->after('nationality');
            $table->string('subdesignation')->nullable()->after('designation');
            $table->text('about')->nullable()->after('subdesignation');
            $table->integer('profile_completeness')->default(0)->after('active_plan_snapshot');
            $table->boolean('is_open_to_work')->default(true)->after('profile_completeness');
        });
    }

    public function down(): void
    {
        Schema::table('job_seeker_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'first_name', 'last_name', 'email', 'phone',
                'nationality', 'designation', 'subdesignation',
                'about', 'profile_completeness', 'is_open_to_work',
            ]);
        });
    }
};
