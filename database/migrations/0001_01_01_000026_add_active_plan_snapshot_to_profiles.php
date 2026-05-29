<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('job_seeker_profiles', function (Blueprint $table) {
            $table->json('active_plan_snapshot')->nullable()->after('key_skills');
        });

        Schema::table('medical_institution_profiles', function (Blueprint $table) {
            $table->json('active_plan_snapshot')->nullable()->after('updated_at');
        });
    }

    public function down(): void
    {
        Schema::table('job_seeker_profiles', function (Blueprint $table) {
            $table->dropColumn('active_plan_snapshot');
        });

        Schema::table('medical_institution_profiles', function (Blueprint $table) {
            $table->dropColumn('active_plan_snapshot');
        });
    }
};
