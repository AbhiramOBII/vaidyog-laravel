<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_seeker_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->string('category_slug')->index();
            $table->string('category_name');
            $table->string('subcategory_name')->index();
            $table->date('date_of_birth')->nullable();
            $table->string('gender')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('pincode')->nullable();
            $table->decimal('experience_years', 4, 1)->nullable();
            $table->string('current_employer')->nullable();
            $table->string('highest_qualification')->nullable();
            $table->string('resume_path')->nullable();
            $table->string('profile_photo_path')->nullable();
            $table->string('created_by_admin_id')->nullable();
            $table->foreign('created_by_admin_id')->references('id')->on('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_seeker_profiles');
    }
};
