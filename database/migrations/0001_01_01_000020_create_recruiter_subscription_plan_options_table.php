<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recruiter_subscription_plan_options', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('recruiter_subscription_plan_id');
            $table->foreign('recruiter_subscription_plan_id', 'rsp_options_plan_id_fk')
                ->references('id')->on('recruiter_subscription_plans')->cascadeOnDelete();
            $table->string('label');
            $table->string('duration_type'); // monthly, yearly
            $table->integer('duration_value')->default(1);
            $table->decimal('price', 10, 2)->default(0.00);
            $table->integer('job_postings_per_month')->nullable();
            $table->boolean('is_unlimited_postings')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recruiter_subscription_plan_options');
    }
};
