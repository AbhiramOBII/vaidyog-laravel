<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('job_seeker_profiles', function (Blueprint $table) {
            if (! Schema::hasColumn('job_seeker_profiles', 'category_slug')) {
                $table->string('category_slug')->nullable()->index()->after('profile_slug');
            }
            if (! Schema::hasColumn('job_seeker_profiles', 'category_name')) {
                $table->string('category_name')->nullable()->after('category_slug');
            }
            if (! Schema::hasColumn('job_seeker_profiles', 'subcategory_name')) {
                $table->string('subcategory_name')->nullable()->index()->after('category_name');
            }
        });
    }

    public function down(): void
    {
        Schema::table('job_seeker_profiles', function (Blueprint $table) {
            $table->dropColumn(array_filter([
                Schema::hasColumn('job_seeker_profiles', 'category_slug')    ? 'category_slug'    : null,
                Schema::hasColumn('job_seeker_profiles', 'category_name')    ? 'category_name'    : null,
                Schema::hasColumn('job_seeker_profiles', 'subcategory_name') ? 'subcategory_name' : null,
            ]));
        });
    }
};
