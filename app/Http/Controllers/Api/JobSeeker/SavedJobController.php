<?php

namespace App\Http\Controllers\Api\JobSeeker;

use App\Http\Controllers\Controller;
use App\Models\JobPosting;
use App\Models\SavedJob;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SavedJobController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $saved = SavedJob::where('user_id', $request->user()->id)
            ->with(['job' => fn ($q) => $q->select('id', 'job_title', 'institution_name', 'location_city', 'location_state', 'slug', 'employment_type', 'salary_min', 'salary_max', 'salary_disclosed', 'is_remote', 'expires_at', 'admin_approved', 'is_active')])
            ->latest('created_at')
            ->paginate($request->integer('per_page', 15));

        return response()->json([
            'success' => true,
            'data'    => $saved,
        ]);
    }

    public function save(Request $request, JobPosting $job): JsonResponse
    {
        $userId    = $request->user()->id;
        $existing  = SavedJob::where('user_id', $userId)->where('job_id', $job->id)->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'Job already saved.',
            ], 409);
        }

        SavedJob::create([
            'user_id'    => $userId,
            'job_id'     => $job->id,
            'created_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Job saved successfully.',
        ], 201);
    }

    public function unsave(Request $request, JobPosting $job): JsonResponse
    {
        $deleted = SavedJob::where('user_id', $request->user()->id)
            ->where('job_id', $job->id)
            ->delete();

        if (! $deleted) {
            return response()->json(['success' => false, 'message' => 'Saved job not found.'], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Job removed from saved list.',
        ]);
    }
}
