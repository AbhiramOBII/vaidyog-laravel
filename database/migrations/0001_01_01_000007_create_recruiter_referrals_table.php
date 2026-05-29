<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recruiter_referrals', function (Blueprint $table) {
            $table->id();
            $table->string('recruiter_id');
            $table->foreign('recruiter_id')->references('id')->on('users')->cascadeOnDelete();
            $table->string('referred_user_id')->nullable();
            $table->foreign('referred_user_id')->references('id')->on('users')->nullOnDelete();
            $table->string('referral_code');
            $table->string('status')->default('pending');
            $table->timestamps();

            $table->index('referral_code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recruiter_referrals');
    }
};
