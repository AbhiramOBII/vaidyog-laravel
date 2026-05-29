<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('featured_job_purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignUlid('recruiter_id')->constrained('users')->cascadeOnDelete();
            $table->foreignUlid('job_id')->constrained('job_postings')->cascadeOnDelete();
            $table->foreignId('featured_job_plan_id')->constrained('featured_job_plans');
            $table->decimal('price_paid', 10, 2);
            $table->foreignId('payment_id')->nullable()->constrained('payments')->nullOnDelete();
            $table->timestamp('featured_from')->nullable();
            $table->timestamp('featured_until')->nullable();
            $table->string('status')->default('pending_payment'); // pending_payment, active, expired
            $table->timestamps();

            $table->index(['recruiter_id', 'status']);
            $table->index(['job_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('featured_job_purchases');
    }
};
