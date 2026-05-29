<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('application_bins', function (Blueprint $table) {
            $table->id();
            $table->ulid('application_id');
            $table->string('deleted_by_type', 20); // admin, recruiter
            $table->ulid('deleted_by_id');
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('application_id')->references('id')->on('job_applications')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('application_bins');
    }
};
