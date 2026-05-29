<?php

namespace Tests\Unit;

use App\Enums\SubscriptionStatusEnum;
use App\Models\JobApplication;
use App\Models\JobPosting;
use App\Models\SubscriptionPlan;
use App\Models\SubscriptionPlanOption;
use App\Models\User;
use App\Models\UserSubscription;
use App\Services\Subscription\SubscriptionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubscriptionServiceTest extends TestCase
{
    use RefreshDatabase;

    private SubscriptionService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(SubscriptionService::class);
    }

    public function test_get_ranking_for_job_seeker_returns_d_when_no_active_plan(): void
    {
        $user = User::factory()->jobSeeker()->create();
        $this->assertEquals('D', $this->service->getRankingForJobSeeker($user));
    }

    public function test_get_remaining_applications_returns_correct_count(): void
    {
        $user = User::factory()->jobSeeker()->create();

        // No plan = Basic (5/month)
        $this->assertEquals(5, $this->service->getRemainingApplicationsThisMonth($user));
    }

    public function test_get_remaining_applications_counts_this_month_applications(): void
    {
        $user = User::factory()->jobSeeker()->create();
        $recruiter = User::factory()->create(['user_type' => 'MedicalInstitution']);

        // Create 3 applications this month (each to a different job)
        for ($i = 0; $i < 3; $i++) {
            $job = JobPosting::factory()->create(['recruiter_id' => $recruiter->id]);
            JobApplication::factory()->create([
                'applicant_id' => $user->id,
                'job_id' => $job->id,
                'recruiter_id' => $recruiter->id,
                'applied_at' => now(),
            ]);
        }

        $this->assertEquals(2, $this->service->getRemainingApplicationsThisMonth($user));
    }

    public function test_assign_plan_to_job_seeker_creates_subscription_with_correct_monthly_expires(): void
    {
        $user = User::factory()->jobSeeker()->create();
        $plan = SubscriptionPlan::create([
            'name' => 'Silver', 'slug' => 'silver', 'ranking' => 'C',
            'description' => [], 'is_active' => true, 'sort_order' => 1,
        ]);
        $option = SubscriptionPlanOption::create([
            'subscription_plan_id' => $plan->id, 'label' => 'Monthly',
            'duration_type' => 'monthly', 'duration_value' => 1,
            'price' => 199, 'applications_per_month' => 20, 'is_unlimited' => false,
            'is_active' => true, 'sort_order' => 1,
        ]);

        $sub = $this->service->assignPlanToJobSeeker($user, $option);

        $this->assertEquals(SubscriptionStatusEnum::Active, $sub->status);
        $this->assertNotNull($sub->expires_at);
        $this->assertTrue($sub->expires_at->isAfter(now()->addDays(27)));
        $this->assertTrue($sub->expires_at->isBefore(now()->addDays(32)));
    }

    public function test_assign_plan_to_job_seeker_creates_subscription_with_correct_yearly_expires(): void
    {
        $user = User::factory()->jobSeeker()->create();
        $plan = SubscriptionPlan::create([
            'name' => 'Gold', 'slug' => 'gold', 'ranking' => 'B',
            'description' => [], 'is_active' => true, 'sort_order' => 2,
        ]);
        $option = SubscriptionPlanOption::create([
            'subscription_plan_id' => $plan->id, 'label' => 'Yearly',
            'duration_type' => 'yearly', 'duration_value' => 1,
            'price' => 4499, 'applications_per_month' => null, 'is_unlimited' => true,
            'is_active' => true, 'sort_order' => 1,
        ]);

        $sub = $this->service->assignPlanToJobSeeker($user, $option);

        $this->assertNull($sub->applications_per_month);
        $this->assertTrue($sub->is_unlimited);
        $this->assertTrue($sub->expires_at->isAfter(now()->addDays(360)));
    }

    public function test_assign_plan_cancels_previous_active_subscription(): void
    {
        $user = User::factory()->jobSeeker()->create();
        $plan = SubscriptionPlan::create([
            'name' => 'Silver', 'slug' => 'silver-2', 'ranking' => 'C',
            'description' => [], 'is_active' => true, 'sort_order' => 1,
        ]);
        $option = SubscriptionPlanOption::create([
            'subscription_plan_id' => $plan->id, 'label' => 'Monthly',
            'duration_type' => 'monthly', 'duration_value' => 1,
            'price' => 199, 'applications_per_month' => 20, 'is_unlimited' => false,
            'is_active' => true, 'sort_order' => 1,
        ]);

        $sub1 = $this->service->assignPlanToJobSeeker($user, $option);
        $sub2 = $this->service->assignPlanToJobSeeker($user, $option);

        $sub1->refresh();
        $this->assertEquals(SubscriptionStatusEnum::Cancelled, $sub1->status);
        $this->assertEquals(SubscriptionStatusEnum::Active, $sub2->status);
    }

    public function test_expire_overdue_subscriptions_marks_expired_correctly(): void
    {
        $user = User::factory()->jobSeeker()->create();
        $plan = SubscriptionPlan::create([
            'name' => 'Test', 'slug' => 'test-exp', 'ranking' => 'C',
            'description' => [], 'is_active' => true, 'sort_order' => 1,
        ]);
        $option = SubscriptionPlanOption::create([
            'subscription_plan_id' => $plan->id, 'label' => 'Monthly',
            'duration_type' => 'monthly', 'duration_value' => 1,
            'price' => 199, 'applications_per_month' => 20, 'is_unlimited' => false,
            'is_active' => true, 'sort_order' => 1,
        ]);

        // Create expired subscription
        $sub = UserSubscription::create([
            'user_id' => $user->id,
            'subscription_plan_id' => $plan->id,
            'subscription_plan_option_id' => $option->id,
            'plan_name' => 'Test',
            'ranking' => 'C',
            'applications_per_month' => 20,
            'is_unlimited' => false,
            'status' => 'active',
            'starts_at' => now()->subMonths(2),
            'expires_at' => now()->subDay(),
        ]);

        $results = $this->service->expireOverdueSubscriptions();

        $this->assertEquals(1, $results['user_subscriptions']);
        $sub->refresh();
        $this->assertEquals(SubscriptionStatusEnum::Expired, $sub->status);
    }

    public function test_can_apply_returns_true_for_unlimited_plan(): void
    {
        $user = User::factory()->jobSeeker()->create();
        $plan = SubscriptionPlan::create([
            'name' => 'Gold', 'slug' => 'gold-apply', 'ranking' => 'B',
            'description' => [], 'is_active' => true, 'sort_order' => 1,
        ]);
        $option = SubscriptionPlanOption::create([
            'subscription_plan_id' => $plan->id, 'label' => 'Monthly',
            'duration_type' => 'monthly', 'duration_value' => 1,
            'price' => 499, 'applications_per_month' => null, 'is_unlimited' => true,
            'is_active' => true, 'sort_order' => 1,
        ]);

        $this->service->assignPlanToJobSeeker($user, $option);

        $this->assertTrue($this->service->canApply($user));
    }
}
