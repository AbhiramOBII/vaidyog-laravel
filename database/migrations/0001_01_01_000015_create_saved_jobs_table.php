<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('saved_jobs', function (Blueprint $table) {
            $table->id();
            $table->ulid('job_id');
            $table->ulid('user_id');
            $table->timestamp('created_at')->useCurrent();

            $table->unique(['job_id', 'user_id']);

            $table->foreign('job_id')->references('id')->on('job_postings')->cascadeOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saved_jobs');
    }
};
