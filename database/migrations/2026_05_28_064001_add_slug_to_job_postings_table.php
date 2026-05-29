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
        Schema::table('job_postings', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('job_title');
        });

        // Backfill slugs for existing rows
        $jobs = DB::table('job_postings')->whereNull('slug')->get();
        foreach ($jobs as $job) {
            $base = Str::slug($job->job_title . ' ' . ($job->location_city ?? ''));
            $slug = $base;
            $i = 1;
            while (DB::table('job_postings')->where('slug', $slug)->where('id', '!=', $job->id)->exists()) {
                $slug = $base . '-' . $i++;
            }
            DB::table('job_postings')->where('id', $job->id)->update(['slug' => $slug]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_postings', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
