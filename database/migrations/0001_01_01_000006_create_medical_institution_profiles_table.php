<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medical_institution_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->string('institution_name');
            $table->string('industry_type')->nullable();
            $table->string('med_type');
            $table->string('contact_person_name')->nullable();
            $table->string('contact_person_email')->nullable();
            $table->string('contact_person_phone')->nullable();
            $table->text('description')->nullable();
            $table->string('logo_path')->nullable();
            $table->string('banner_image_path')->nullable();
            $table->integer('employee_count')->nullable();
            $table->json('specialties')->nullable();
            $table->json('accreditations')->nullable();
            $table->string('address_line1')->nullable();
            $table->string('address_line2')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('pincode')->nullable();
            $table->string('website_url')->nullable();
            $table->text('additional_information')->nullable();
            $table->string('referral_code')->nullable()->unique();
            $table->boolean('is_featured')->default(false);
            $table->timestamp('featured_at')->nullable();
            $table->boolean('is_profile_completed')->default(false);
            $table->string('created_by_admin_id')->nullable();
            $table->foreign('created_by_admin_id')->references('id')->on('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medical_institution_profiles');
    }
};
