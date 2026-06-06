<?php

namespace App\Http\Controllers\Api\JobSeeker;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use App\Models\SavedJob;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user   = $request->user()->load('jobSeekerProfile');
        $userId = $user->id;

        $baseQuery = JobApplication::forApplicant($userId);

        $stats = [
            'total_applications'  => (clone $baseQuery)->count(),
            'under_review'        => (clone $baseQuery)->whereIn('status', ['applied', 'reviewed', 'shortlisted'])->count(),
            'interviews'          => (clone $baseQuery)->whereIn('status', ['interviewed', 'scheduled'])->count(),
            'offers'              => (clone $baseQuery)->byStatus('offered')->count(),
            'rejected'            => (clone $baseQuery)->byStatus('rejected')->count(),
            'saved_jobs'          => SavedJob::where('user_id', $userId)->count(),
        ];

        $recentApplications = JobApplication::forApplicant($userId)
            ->with(['job' => fn ($q) => $q->select('id', 'job_title', 'institution_name', 'location_city', 'location_state', 'slug')])
            ->latest('applied_at')
            ->limit(5)
            ->get()
            ->map(fn ($app) => [
                'id'         => $app->id,
                'status'     => $app->status?->value,
                'applied_at' => $app->applied_at,
                'job'        => $app->job ? [
                    'title'         => $app->job->job_title,
                    'institution'   => $app->job->institution_name,
                    'location'      => trim(($app->job->location_city ?? '') . ', ' . ($app->job->location_state ?? ''), ', '),
                    'slug'          => $app->job->slug,
                ] : null,
            ]);

        $profile = $user->jobSeekerProfile;

        return response()->json([
            'success' => true,
            'data'    => [
                'stats'               => $stats,
                'recent_applications' => $recentApplications,
                'profile_summary'     => $profile ? [
                    'full_name'            => $profile->getFullName(),
                    'profile_completeness' => $profile->profile_completeness,
                    'completeness_label'   => $profile->getCompletenessLabel(),
                    'completeness_color'   => $profile->getCompletenessColor(),
                    'is_open_to_work'      => $profile->is_open_to_work,
                    'photo_url'            => $profile->getProfilePictureUrl(),
                    'designation'          => $profile->designation,
                ] : null,
            ],
        ]);
    }
}
