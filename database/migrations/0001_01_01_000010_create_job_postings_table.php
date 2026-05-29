<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_postings', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('recruiter_id')->constrained('users')->cascadeOnDelete();

            // Core fields
            $table->string('job_title');
            $table->longText('job_description');
            $table->json('key_skills')->nullable();
            $table->string('employment_type')->default('full_time');
            $table->decimal('experience_min', 4, 1)->nullable();
            $table->decimal('experience_max', 4, 1)->nullable();

            // Location
            $table->string('location_city')->nullable();
            $table->string('location_state')->nullable();
            $table->string('location_office_address')->nullable();
            $table->string('location_pincode')->nullable();
            $table->boolean('is_remote')->default(false);

            // Salary
            $table->integer('salary_min')->nullable();
            $table->integer('salary_max')->nullable();
            $table->string('salary_currency', 10)->default('INR');
            $table->boolean('salary_disclosed')->default(true);

            // Denormalized recruiter info
            $table->string('institution_name')->nullable();
            $table->string('contact_name')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();

            // Qualification arrays
            $table->json('educational_requirements')->nullable();
            $table->json('medical_qualifications')->nullable();
            $table->json('certifications_required')->nullable();
            $table->json('specialties')->nullable();
            $table->json('perks_and_benefits')->nullable();

            // Posting metadata
            $table->integer('posting_duration_days')->default(30);
            $table->timestamp('expires_at')->nullable();
            $table->integer('number_of_vacancies')->default(1);

            // Taxonomy
            $table->string('category_slug')->nullable()->index();
            $table->string('subcategory_name')->nullable()->index();

            // Approval
            $table->boolean('admin_approved')->default(false);
            $table->timestamp('approved_at')->nullable();
            $table->foreignUlid('approved_by_admin_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('rejection_reason')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->foreignUlid('rejected_by_admin_id')->nullable()->constrained('users')->nullOnDelete();

            // Status flags
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->timestamp('featured_at')->nullable();
            $table->boolean('is_deleted')->default(false);

            $table->softDeletes();
            $table->timestamps();

            $table->index(['admin_approved', 'is_active', 'expires_at']);
            $table->index('recruiter_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_postings');
    }
};
