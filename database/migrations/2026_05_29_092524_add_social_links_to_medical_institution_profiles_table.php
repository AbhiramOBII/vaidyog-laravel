<?php

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
        Schema::table('medical_institution_profiles', function (Blueprint $table) {
            $table->string('social_facebook', 500)->nullable()->after('website_url');
            $table->string('social_twitter', 500)->nullable()->after('social_facebook');
            $table->string('social_linkedin', 500)->nullable()->after('social_twitter');
            $table->string('social_instagram', 500)->nullable()->after('social_linkedin');
            $table->string('social_youtube', 500)->nullable()->after('social_instagram');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('medical_institution_profiles', function (Blueprint $table) {
            $table->dropColumn(['social_facebook', 'social_twitter', 'social_linkedin', 'social_instagram', 'social_youtube']);
        });
    }
};
