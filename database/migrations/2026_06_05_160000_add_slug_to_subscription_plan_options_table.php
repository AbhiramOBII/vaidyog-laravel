<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subscription_plan_options', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('label');
        });

        // Backfill slugs for existing rows
        foreach (\App\Models\SubscriptionPlanOption::with('plan')->get() as $option) {
            $base = Str::slug($option->plan->name . '-' . $option->label);
            $slug = $base;
            $i = 1;
            while (\App\Models\SubscriptionPlanOption::where('slug', $slug)->where('id', '!=', $option->id)->exists()) {
                $slug = $base . '-' . $i++;
            }
            $option->update(['slug' => $slug]);
        }
    }

    public function down(): void
    {
        Schema::table('subscription_plan_options', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
