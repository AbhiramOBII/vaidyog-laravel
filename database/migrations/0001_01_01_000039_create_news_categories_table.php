<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('news_categories', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('short_description', 500)->nullable();
            $table->longText('full_description')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->string('thumbnail_image')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_description', 500)->nullable();
            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('news_categories')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('news_categories');
    }
};
