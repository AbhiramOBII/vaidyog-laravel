<?php

namespace App\Http\Controllers\Api\Recruiter;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use App\Models\JobPosting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $recruiter   = $request->user();
        $recruiterId = $recruiter->id;

        $jobStats = [
            'total'    => JobPosting::forRecruiter($recruiterId)->count(),
            'live'     => JobPosting::forRecruiter($recruiterId)->approved()->active()->notExpired()->count(),
            'pending'  => JobPosting::forRecruiter($recruiterId)->pending()->count(),
            'expired'  => JobPosting::forRecruiter($recruiterId)->expired()->count(),
            'rejected' => JobPosting::forRecruiter($recruiterId)->rejected()->count(),
        ];

        $applicationStats = [
            'total'       => JobApplication::forRecruiter($recruiterId)->count(),
            'new'         => JobApplication::forRecruiter($recruiterId)->byStatus('applied')->count(),
            'shortlisted' => JobApplication::forRecruiter($recruiterId)->byStatus('shortlisted')->count(),
            'interviewed' => JobApplication::forRecruiter($recruiterId)->whereIn('status', ['interviewed', 'scheduled'])->count(),
            'offered'     => JobApplication::forRecruiter($recruiterId)->byStatus('offered')->count(),
        ];

        $recentJobs = JobPosting::forRecruiter($recruiterId)
            ->with(['applications' => fn ($q) => $q->select('id', 'job_id', 'status')])
            ->latest()
            ->limit(5)
            ->get(['id', 'job_title', 'slug', 'admin_approved', 'is_active', 'expires_at', 'rejection_reason', 'created_at'])
            ->map(fn ($job) => [
                'id'                => $job->id,
                'slug'              => $job->slug,
                'title'             => $job->job_title,
                'status'            => $job->getDisplayStatus(),
                'applications_count'=> $job->applications->count(),
                'created_at'        => $job->created_at,
            ]);

        $recentApplications = JobApplication::forRecruiter($recruiterId)
            ->with(['job:id,job_title,slug', 'applicant:id,name,email'])
            ->latest('applied_at')
            ->limit(5)
            ->get()
            ->map(fn ($app) => [
                'id'         => $app->id,
                'status'     => $app->status?->value,
                'applied_at' => $app->applied_at,
                'job_title'  => $app->job?->job_title,
                'applicant'  => $app->applicant ? ['name' => $app->applicant->name] : null,
            ]);

        $subscription = $recruiter->activeRecruiterSubscription();

        return response()->json([
            'success' => true,
            'data'    => [
                'job_stats'           => $jobStats,
                'application_stats'   => $applicationStats,
                'recent_jobs'         => $recentJobs,
                'recent_applications' => $recentApplications,
                'active_plan'         => $subscription ? [
                    'plan_name'  => $subscription->plan_name,
                    'status'     => $subscription->status?->value,
                    'expires_at' => $subscription->expires_at,
                    'is_active'  => $subscription->isActive(),
                ] : null,
            ],
        ]);
    }
}
