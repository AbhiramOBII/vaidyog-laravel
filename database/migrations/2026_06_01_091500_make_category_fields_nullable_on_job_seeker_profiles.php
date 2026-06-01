<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('job_seeker_profiles', function (Blueprint $table) {
            $table->string('category_slug')->nullable()->change();
            $table->string('category_name')->nullable()->change();
            $table->string('subcategory_name')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('job_seeker_profiles', function (Blueprint $table) {
            $table->string('category_slug')->nullable(false)->change();
            $table->string('category_name')->nullable(false)->change();
            $table->string('subcategory_name')->nullable(false)->change();
        });
    }
};
