<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscription_plan_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscription_plan_id')->constrained('subscription_plans')->cascadeOnDelete();
            $table->string('label'); // e.g. "Monthly", "Yearly"
            $table->string('duration_type'); // monthly, yearly, lifetime
            $table->integer('duration_value')->default(1);
            $table->decimal('price', 10, 2)->default(0.00);
            $table->integer('applications_per_month')->nullable();
            $table->boolean('is_unlimited')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscription_plan_options');
    }
};
