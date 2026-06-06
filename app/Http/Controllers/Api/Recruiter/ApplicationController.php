<?php

namespace App\Http\Controllers\Api\Recruiter;

use App\Enums\ApplicationRankingEnum;
use App\Enums\ApplicationStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use App\Models\JobPosting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ApplicationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $recruiterId = $request->user()->id;
        $query       = JobApplication::forRecruiter($recruiterId)
            ->with([
                'job:id,job_title,slug',
                'applicant:id,name,email',
                'applicant.jobSeekerProfile:user_id,first_name,last_name,salutation,designation,profile_photo_path,experience_years,city,state',
            ]);

        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        if ($request->filled('ranking')) {
            $query->byRanking($request->ranking);
        }

        if ($request->filled('job_id')) {
            $query->forJob($request->job_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('applicant', fn ($q) => $q->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%"));
        }

        $applications = $query->orderedByRanking()
            ->latest('applied_at')
            ->paginate($request->integer('per_page', 15));

        return response()->json([
            'success' => true,
            'data'    => $applications,
            'meta'    => [
                'statuses' => collect(ApplicationStatusEnum::cases())->map(fn ($e) => ['value' => $e->value, 'label' => $e->label()]),
                'rankings' => collect(ApplicationRankingEnum::cases())->map(fn ($e) => ['value' => $e->value, 'label' => $e->value]),
            ],
        ]);
    }

    public function show(Request $request, JobApplication $application): JsonResponse
    {
        if (! $application->isOwnedByRecruiter($request->user()->id)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }

        $application->load([
            'job',
            'applicant',
            'applicant.jobSeekerProfile',
            'applicant.educations',
            'applicant.employments',
            'applicant.certifications',
            'applicant.languages',
        ]);

        return response()->json([
            'success' => true,
            'data'    => $application,
        ]);
    }

    public function forJob(Request $request, JobPosting $job): JsonResponse
    {
        if ($job->recruiter_id !== $request->user()->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }

        $query = JobApplication::forJob($job->id)
            ->with([
                'applicant:id,name,email',
                'applicant.jobSeekerProfile:user_id,first_name,last_name,salutation,designation,profile_photo_path,experience_years,city,state,key_skills',
            ]);

        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        $applications = $query->orderedByRanking()
            ->latest('applied_at')
            ->paginate($request->integer('per_page', 20));

        return response()->json([
            'success' => true,
            'data'    => $applications,
            'job'     => $job->only(['id', 'job_title', 'slug', 'admin_approved', 'is_active']),
        ]);
    }

    public function updateStatus(Request $request, JobApplication $application): JsonResponse
    {
        if (! $application->isOwnedByRecruiter($request->user()->id)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }

        $validated = $request->validate([
            'status' => ['required', Rule::in(array_column(ApplicationStatusEnum::cases(), 'value'))],
            'notes'  => ['nullable', 'string', 'max:1000'],
        ]);

        $application->updateStatus($validated['status']);

        if (! empty($validated['notes'])) {
            $application->update(['recruiter_notes' => $validated['notes']]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Application status updated.',
            'data'    => $application->fresh(),
        ]);
    }

    public function updateRanking(Request $request, JobApplication $application): JsonResponse
    {
        if (! $application->isOwnedByRecruiter($request->user()->id)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }

        $validated = $request->validate([
            'ranking' => ['required', Rule::in(array_column(ApplicationRankingEnum::cases(), 'value'))],
        ]);

        $application->update(['ranking' => $validated['ranking']]);

        return response()->json([
            'success' => true,
            'message' => 'Ranking updated.',
            'data'    => $application->fresh(),
        ]);
    }
}
