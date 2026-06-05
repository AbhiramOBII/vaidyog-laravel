<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admin_roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->string('description')->nullable();
            $table->json('permissions')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Add role_id to users table for sub-admins
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('admin_role_id')->nullable()->after('user_type');
            $table->foreign('admin_role_id')->references('id')->on('admin_roles')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['admin_role_id']);
            $table->dropColumn('admin_role_id');
        });

        Schema::dropIfExists('admin_roles');
    }
};
