<?php

namespace App\Http\Controllers\Api\JobSeeker;

use App\Enums\ApplicationStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use App\Models\JobPosting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ApplicationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $userId = $request->user()->id;
        $query  = JobApplication::forApplicant($userId)
            ->with(['job' => fn ($q) => $q->select('id', 'job_title', 'institution_name', 'location_city', 'location_state', 'slug', 'employment_type', 'is_remote')]);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('job', fn ($q) => $q->where('job_title', 'like', "%{$search}%")
                ->orWhere('institution_name', 'like', "%{$search}%"));
        }

        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        $baseQuery = JobApplication::forApplicant($userId);
        $stats     = [
            'total'       => (clone $baseQuery)->count(),
            'under_review'=> (clone $baseQuery)->whereIn('status', ['applied', 'reviewed', 'shortlisted'])->count(),
            'interviews'  => (clone $baseQuery)->whereIn('status', ['interviewed', 'scheduled'])->count(),
            'offers'      => (clone $baseQuery)->byStatus('offered')->count(),
        ];

        $applications = $query->latest('applied_at')->paginate($request->integer('per_page', 15));

        return response()->json([
            'success' => true,
            'data'    => [
                'applications' => $applications,
                'stats'        => $stats,
                'statuses'     => collect(ApplicationStatusEnum::cases())->map(fn ($e) => ['value' => $e->value, 'label' => $e->label()]),
            ],
        ]);
    }

    public function show(Request $request, JobApplication $application): JsonResponse
    {
        if ($application->applicant_id !== $request->user()->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }

        $application->load(['job.recruiter.profile']);

        return response()->json([
            'success' => true,
            'data'    => $application,
        ]);
    }

    public function apply(Request $request, JobPosting $job): JsonResponse
    {
        $user = $request->user();

        if (! $job->admin_approved || ! $job->is_active) {
            return response()->json(['success' => false, 'message' => 'This job is not accepting applications.'], 422);
        }

        if ($job->expires_at && $job->expires_at->isPast()) {
            return response()->json(['success' => false, 'message' => 'This job posting has expired.'], 422);
        }

        $alreadyApplied = JobApplication::forApplicant($user->id)->forJob($job->id)->exists();
        if ($alreadyApplied) {
            return response()->json(['success' => false, 'message' => 'You have already applied for this job.'], 409);
        }

        $validated = $request->validate([
            'cover_note'  => ['nullable', 'string', 'max:2000'],
            'resume'      => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:5120'],
        ]);

        $resumePath = null;
        if ($request->hasFile('resume')) {
            $resumePath = $request->file('resume')->store('application-resumes', 'public');
        } else {
            $resumePath = $user->jobSeekerProfile?->resume_path;
        }

        $profile    = $user->jobSeekerProfile;
        $jobSkills  = $job->key_skills ?? [];
        $userSkills = $profile?->key_skills ?? [];
        $matching   = array_values(array_intersect($jobSkills, $userSkills));

        $application = JobApplication::create([
            'job_id'         => $job->id,
            'applicant_id'   => $user->id,
            'recruiter_id'   => $job->recruiter_id,
            'status'         => ApplicationStatusEnum::Applied,
            'cover_note'     => $validated['cover_note'] ?? null,
            'resume_path'    => $resumePath,
            'matching_skills'=> $matching ?: null,
            'status_dates'   => ['applied' => now()->toIso8601String()],
            'applied_at'     => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Application submitted successfully.',
            'data'    => $application,
        ], 201);
    }

    public function withdraw(Request $request, JobApplication $application): JsonResponse
    {
        if ($application->applicant_id !== $request->user()->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }

        if ($application->status?->isTerminal()) {
            return response()->json(['success' => false, 'message' => 'Cannot withdraw a finalised application.'], 422);
        }

        $application->delete();

        return response()->json([
            'success' => true,
            'message' => 'Application withdrawn successfully.',
        ]);
    }
}
