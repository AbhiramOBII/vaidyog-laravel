<?php

namespace App\Http\Controllers\Api\Recruiter;

use App\Http\Controllers\Controller;
use App\Models\RecruiterSubscriptionPlan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $recruiter   = $request->user();
        $profile     = $recruiter->profile;
        $medType     = $profile?->med_type?->value;

        $query = RecruiterSubscriptionPlan::where('is_active', true)
            ->with(['options' => fn ($q) => $q->where('is_active', true)->orderBy('sort_order')]);

        if ($medType) {
            $query->where(function ($q) use ($medType) {
                $q->where('recruiter_type', $medType)->orWhereNull('recruiter_type');
            });
        }

        $plans = $query->orderBy('sort_order')->get();

        return response()->json([
            'success' => true,
            'data'    => $plans,
        ]);
    }

    public function show(Request $request): JsonResponse
    {
        $recruiter    = $request->user();
        $subscription = $recruiter->activeRecruiterSubscription();

        if (! $subscription) {
            return response()->json([
                'success' => true,
                'data'    => null,
                'message' => 'No active plan.',
            ]);
        }

        $subscription->load(['plan', 'option']);

        return response()->json([
            'success' => true,
            'data'    => [
                'plan_name'               => $subscription->plan_name,
                'recruiter_type'          => $subscription->recruiter_type?->value,
                'job_postings_per_month'  => $subscription->job_postings_per_month,
                'is_unlimited_postings'   => $subscription->is_unlimited_postings,
                'status'                  => $subscription->status?->value,
                'starts_at'               => $subscription->starts_at,
                'expires_at'              => $subscription->expires_at,
                'is_active'               => $subscription->isActive(),
                'plan'                    => $subscription->plan,
                'option'                  => $subscription->option,
                'snapshot'                => $recruiter->profile?->active_plan_snapshot,
            ],
        ]);
    }
}
