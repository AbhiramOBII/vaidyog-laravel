<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('featured_job_plans', function (Blueprint $table) {
            $table->id();
            $table->string('recruiter_type')->nullable(); // null = applies to all
            $table->string('name');
            $table->decimal('price_per_post', 10, 2);
            $table->integer('featured_duration_days')->default(30);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('recruiter_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('featured_job_plans');
    }
};
