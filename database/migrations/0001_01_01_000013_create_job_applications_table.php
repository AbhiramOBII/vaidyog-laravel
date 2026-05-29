<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_applications', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->ulid('job_id');
            $table->ulid('applicant_id');
            $table->ulid('recruiter_id');
            $table->string('status', 20)->default('applied');
            $table->char('ranking', 1)->default('D');
            $table->json('matching_skills')->nullable();
            $table->text('cover_note')->nullable();
            $table->string('resume_path')->nullable();
            $table->json('status_dates')->nullable();
            $table->text('recruiter_notes')->nullable();
            $table->timestamp('applied_at');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['job_id', 'applicant_id'], 'unique_application');

            $table->foreign('job_id')->references('id')->on('job_postings')->cascadeOnDelete();
            $table->foreign('applicant_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('recruiter_id')->references('id')->on('users')->cascadeOnDelete();

            $table->index(['recruiter_id', 'status']);
            $table->index(['applicant_id', 'status']);
            $table->index(['job_id', 'ranking', 'applied_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_applications');
    }
};
