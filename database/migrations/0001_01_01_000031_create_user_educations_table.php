<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_educations', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->string('degree');
            $table->string('university')->nullable();
            $table->string('course')->nullable();
            $table->string('specialization')->nullable();
            $table->string('course_type')->default('full_time');
            $table->integer('course_duration_start')->nullable();
            $table->integer('course_duration_end')->nullable();
            $table->string('grading_system')->default('percentage');
            $table->string('grade')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_educations');
    }
};
