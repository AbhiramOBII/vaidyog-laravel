<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('job_seeker_profiles', function (Blueprint $table) {
            $table->string('country')->nullable()->after('state');
        });

        // Backfill existing profiles with India as default
        \App\Models\JobSeekerProfile::whereNull('country')->update(['country' => 'India']);
    }

    public function down(): void
    {
        Schema::table('job_seeker_profiles', function (Blueprint $table) {
            $table->dropColumn('country');
        });
    }
};
