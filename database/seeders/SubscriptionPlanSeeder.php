<?php

namespace Database\Seeders;

use App\Models\FeaturedJobPlan;
use App\Models\RecruiterSubscriptionPlan;
use App\Models\SubscriptionPlan;
use Illuminate\Database\Seeder;

class SubscriptionPlanSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedJobSeekerPlans();
        $this->seedRecruiterPlans();
        $this->seedFeaturedJobPlans();
    }

    private function seedJobSeekerPlans(): void
    {
        // Basic (Free)
        $basic = SubscriptionPlan::updateOrCreate(['slug' => 'basic'], [
            'name' => 'Basic',
            'ranking' => 'D',
            'description' => ['Apply to 5 jobs per month', 'Standard profile visibility', 'Basic job recommendations'],
            'is_active' => true,
            'is_recommended' => false,
            'sort_order' => 1,
        ]);
        $basic->options()->updateOrCreate(['label' => 'Free'], [
            'duration_type' => 'lifetime',
            'duration_value' => 1,
            'price' => 0.00,
            'applications_per_month' => 5,
            'is_unlimited' => false,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        // Silver
        $silver = SubscriptionPlan::updateOrCreate(['slug' => 'silver'], [
            'name' => 'Silver',
            'ranking' => 'C',
            'description' => ['Apply to 20 jobs per month', 'Enhanced profile visibility', 'Priority in recruiter search'],
            'is_active' => true,
            'is_recommended' => false,
            'sort_order' => 2,
        ]);
        $silver->options()->updateOrCreate(['label' => 'Monthly'], [
            'duration_type' => 'monthly',
            'duration_value' => 1,
            'price' => 199.00,
            'applications_per_month' => 20,
            'is_unlimited' => false,
            'is_active' => true,
            'sort_order' => 1,
        ]);
        $silver->options()->updateOrCreate(['label' => 'Yearly'], [
            'duration_type' => 'yearly',
            'duration_value' => 1,
            'price' => 1799.00,
            'applications_per_month' => 20,
            'is_unlimited' => false,
            'is_active' => true,
            'sort_order' => 2,
        ]);

        // Gold
        $gold = SubscriptionPlan::updateOrCreate(['slug' => 'gold'], [
            'name' => 'Gold',
            'ranking' => 'B',
            'description' => ['Unlimited job applications', 'Top profile visibility', 'Featured in recruiter search', 'Resume highlight'],
            'is_active' => true,
            'is_recommended' => true,
            'sort_order' => 3,
        ]);
        $gold->options()->updateOrCreate(['label' => 'Monthly'], [
            'duration_type' => 'monthly',
            'duration_value' => 1,
            'price' => 499.00,
            'applications_per_month' => null,
            'is_unlimited' => true,
            'is_active' => true,
            'sort_order' => 1,
        ]);
        $gold->options()->updateOrCreate(['label' => 'Yearly'], [
            'duration_type' => 'yearly',
            'duration_value' => 1,
            'price' => 4499.00,
            'applications_per_month' => null,
            'is_unlimited' => true,
            'is_active' => true,
            'sort_order' => 2,
        ]);

        // Platinum
        $platinum = SubscriptionPlan::updateOrCreate(['slug' => 'platinum'], [
            'name' => 'Platinum',
            'ranking' => 'A',
            'description' => ['Unlimited applications', 'Highest ranking (A)', 'Profile badge', 'Dedicated support', 'Early access to featured jobs'],
            'is_active' => true,
            'is_recommended' => false,
            'sort_order' => 4,
        ]);
        $platinum->options()->updateOrCreate(['label' => 'Monthly'], [
            'duration_type' => 'monthly',
            'duration_value' => 1,
            'price' => 999.00,
            'applications_per_month' => null,
            'is_unlimited' => true,
            'is_active' => true,
            'sort_order' => 1,
        ]);
        $platinum->options()->updateOrCreate(['label' => 'Yearly'], [
            'duration_type' => 'yearly',
            'duration_value' => 1,
            'price' => 8999.00,
            'applications_per_month' => null,
            'is_unlimited' => true,
            'is_active' => true,
            'sort_order' => 2,
        ]);
    }

    private function seedRecruiterPlans(): void
    {
        $plans = [
            ['type' => 'clinics', 'name' => 'Clinics', 'monthly_price' => 999, 'yearly_price' => 8999, 'monthly_posts' => 5, 'yearly_posts' => 8],
            ['type' => 'small_hospital', 'name' => 'Small Hospital', 'monthly_price' => 1999, 'yearly_price' => 17999, 'monthly_posts' => 15, 'yearly_posts' => 20],
            ['type' => 'larger_hospital', 'name' => 'Larger Hospital', 'monthly_price' => 3999, 'yearly_price' => 35999, 'monthly_posts' => 40, 'yearly_posts' => 50],
            ['type' => 'enterprise', 'name' => 'Enterprise', 'monthly_price' => 7999, 'yearly_price' => 71999, 'monthly_posts' => null, 'yearly_posts' => null],
            ['type' => 'enterprise_branch', 'name' => 'Enterprise Branch', 'monthly_price' => 4999, 'yearly_price' => 44999, 'monthly_posts' => null, 'yearly_posts' => null],
        ];

        foreach ($plans as $i => $p) {
            $plan = RecruiterSubscriptionPlan::updateOrCreate(['slug' => $p['type'] . '-plan'], [
                'name' => $p['name'] . ' Plan',
                'recruiter_type' => $p['type'],
                'description' => ['Post jobs for ' . $p['name'] . ' institutions', 'Priority listing', 'Applicant management tools'],
                'is_active' => true,
                'sort_order' => $i + 1,
            ]);

            $plan->options()->updateOrCreate(['label' => 'Monthly'], [
                'duration_type' => 'monthly',
                'duration_value' => 1,
                'price' => $p['monthly_price'],
                'job_postings_per_month' => $p['monthly_posts'],
                'is_unlimited_postings' => $p['monthly_posts'] === null,
                'is_active' => true,
                'sort_order' => 1,
            ]);

            $plan->options()->updateOrCreate(['label' => 'Yearly'], [
                'duration_type' => 'yearly',
                'duration_value' => 1,
                'price' => $p['yearly_price'],
                'job_postings_per_month' => $p['yearly_posts'],
                'is_unlimited_postings' => $p['yearly_posts'] === null,
                'is_active' => true,
                'sort_order' => 2,
            ]);
        }
    }

    private function seedFeaturedJobPlans(): void
    {
        FeaturedJobPlan::updateOrCreate(['name' => 'Featured Job Slot (30 days)', 'recruiter_type' => null], [
            'price_per_post' => 499.00,
            'featured_duration_days' => 30,
            'is_active' => true,
        ]);

        FeaturedJobPlan::updateOrCreate(['name' => 'Featured Slot - Clinics', 'recruiter_type' => 'clinics'], [
            'price_per_post' => 299.00,
            'featured_duration_days' => 30,
            'is_active' => true,
        ]);

        FeaturedJobPlan::updateOrCreate(['name' => 'Featured Slot - Enterprise', 'recruiter_type' => 'enterprise'], [
            'price_per_post' => 999.00,
            'featured_duration_days' => 30,
            'is_active' => true,
        ]);
    }
}
