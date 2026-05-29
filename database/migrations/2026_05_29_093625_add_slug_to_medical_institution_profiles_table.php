<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('medical_institution_profiles', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('institution_name');
        });

        // Generate slugs for existing profiles
        $profiles = DB::table('medical_institution_profiles')->get();
        foreach ($profiles as $profile) {
            $base = Str::slug($profile->institution_name . ' ' . ($profile->city ?? ''));
            $slug = $base;
            $i = 1;
            while (DB::table('medical_institution_profiles')->where('slug', $slug)->where('id', '!=', $profile->id)->exists()) {
                $slug = $base . '-' . $i++;
            }
            DB::table('medical_institution_profiles')->where('id', $profile->id)->update(['slug' => $slug]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('medical_institution_profiles', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
