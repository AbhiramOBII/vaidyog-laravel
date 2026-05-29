<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_employments', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->string('company_name');
            $table->string('job_title');
            $table->string('employment_type')->default('full_time');
            $table->boolean('is_current')->default(false);
            $table->date('joining_date')->nullable();
            $table->date('leaving_date')->nullable();
            $table->integer('total_experience_months')->nullable();
            $table->decimal('current_salary', 10, 2)->nullable();
            $table->string('salary_currency')->default('INR');
            $table->text('responsibilities')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_employments');
    }
};
