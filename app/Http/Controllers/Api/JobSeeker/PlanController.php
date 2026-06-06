<?php

namespace App\Http\Controllers\Api\JobSeeker;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $plans = SubscriptionPlan::where('is_active', true)
            ->with(['options' => fn ($q) => $q->where('is_active', true)->orderBy('sort_order')])
            ->orderBy('sort_order')
            ->get();

        return response()->json([
            'success' => true,
            'data'    => $plans,
        ]);
    }

    public function show(Request $request): JsonResponse
    {
        $user         = $request->user();
        $subscription = $user->activeSubscription();

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
                'plan_name'  => $subscription->plan?->name,
                'status'     => $subscription->status?->value,
                'starts_at'  => $subscription->starts_at,
                'expires_at' => $subscription->expires_at,
                'is_active'  => $subscription->isActive(),
                'option'     => $subscription->option,
                'snapshot'   => $user->jobSeekerProfile?->active_plan_snapshot,
            ],
        ]);
    }
}
