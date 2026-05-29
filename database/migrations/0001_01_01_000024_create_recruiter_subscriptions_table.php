<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recruiter_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignUlid('recruiter_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedBigInteger('recruiter_subscription_plan_id');
            $table->foreign('recruiter_subscription_plan_id', 'rec_subs_plan_id_fk')
                ->references('id')->on('recruiter_subscription_plans');
            $table->unsignedBigInteger('recruiter_subscription_plan_option_id');
            $table->foreign('recruiter_subscription_plan_option_id', 'rec_subs_plan_option_id_fk')
                ->references('id')->on('recruiter_subscription_plan_options');
            $table->string('plan_name'); // snapshot
            $table->string('recruiter_type'); // snapshot
            $table->integer('job_postings_per_month')->nullable();
            $table->boolean('is_unlimited_postings')->default(false);
            $table->string('status')->default('active');
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->foreignId('payment_id')->nullable()->constrained('payments')->nullOnDelete();
            $table->boolean('assigned_by_admin')->default(false);
            $table->timestamps();

            $table->index(['recruiter_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recruiter_subscriptions');
    }
};
