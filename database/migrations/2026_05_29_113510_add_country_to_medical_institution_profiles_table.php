<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('medical_institution_profiles', function (Blueprint $table) {
            $table->string('country')->nullable()->after('address_line2');
        });
    }

    public function down(): void
    {
        Schema::table('medical_institution_profiles', function (Blueprint $table) {
            $table->dropColumn('country');
        });
    }
};
