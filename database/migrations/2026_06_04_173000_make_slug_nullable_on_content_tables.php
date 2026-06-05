<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tables = [
            'blog_categories',
            'blogs',
            'event_categories',
            'events',
            'news_categories',
            'news',
            'job_subcategories',
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'slug')) {
                Schema::table($table, function (Blueprint $t) {
                    $t->string('slug')->nullable()->change();
                });
            }
        }
    }

    public function down(): void
    {
        $tables = [
            'blog_categories',
            'blogs',
            'event_categories',
            'events',
            'news_categories',
            'news',
            'job_subcategories',
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'slug')) {
                Schema::table($table, function (Blueprint $t) {
                    $t->string('slug')->nullable(false)->change();
                });
            }
        }
    }
};
