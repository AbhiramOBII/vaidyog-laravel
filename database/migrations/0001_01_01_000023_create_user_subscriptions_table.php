<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignUlid('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('subscription_plan_id')->constrained('subscription_plans');
            $table->foreignId('subscription_plan_option_id')->constrained('subscription_plan_options');
            $table->string('plan_name'); // snapshot
            $table->string('ranking', 1); // snapshot A/B/C/D
            $table->integer('applications_per_month')->nullable();
            $table->boolean('is_unlimited')->default(false);
            $table->string('status')->default('active'); // active, expired, cancelled, pending_payment
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('expires_at')->nullable(); // null for lifetime
            $table->foreignId('payment_id')->nullable()->constrained('payments')->nullOnDelete();
            $table->boolean('assigned_by_admin')->default(false);
            $table->timestamps();

            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_subscriptions');
    }
};
