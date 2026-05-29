<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_bins', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('job_id')->constrained('job_postings')->cascadeOnDelete();
            $table->string('deleted_by_type'); // admin or recruiter
            $table->foreignUlid('deleted_by_id')->constrained('users')->cascadeOnDelete();
            $table->json('original_data');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_bins');
    }
};
