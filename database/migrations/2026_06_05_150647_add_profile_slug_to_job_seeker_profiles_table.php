<?php

use App\Models\JobSeekerProfile;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('job_seeker_profiles', function (Blueprint $table) {
            $table->string('profile_slug')->nullable()->unique()->after('salutation');
        });

        // Generate slugs for existing profiles
        JobSeekerProfile::whereNull('profile_slug')->each(function ($profile) {
            $profile->update(['profile_slug' => $profile->generateProfileSlug()]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_seeker_profiles', function (Blueprint $table) {
            $table->dropColumn('profile_slug');
        });
    }
};
