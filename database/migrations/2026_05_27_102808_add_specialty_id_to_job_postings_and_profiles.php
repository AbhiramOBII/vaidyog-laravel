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
        Schema::table('job_postings', function (Blueprint $table) {
            $table->foreignId('specialty_id')->nullable()->after('subcategory_name')
                  ->constrained('specialties')->nullOnDelete();
        });

        Schema::table('job_seeker_profiles', function (Blueprint $table) {
            $table->foreignId('specialty_id')->nullable()->after('subcategory_name')
                  ->constrained('specialties')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_postings', function (Blueprint $table) {
            $table->dropForeign(['specialty_id']);
            $table->dropColumn('specialty_id');
        });

        Schema::table('job_seeker_profiles', function (Blueprint $table) {
            $table->dropForeign(['specialty_id']);
            $table->dropColumn('specialty_id');
        });
    }
};
