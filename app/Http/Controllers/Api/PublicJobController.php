<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JobPosting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PublicJobController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = JobPosting::publiclyVisible()
            ->with(['recruiter.profile:user_id,institution_name,logo_path,city,state', 'specialty:id,name']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('job_title', 'like', "%{$search}%")
                    ->orWhere('institution_name', 'like', "%{$search}%")
                    ->orWhere('location_city', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category_slug', $request->category);
        }

        if ($request->filled('specialty_id')) {
            $query->where('specialty_id', $request->specialty_id);
        }

        if ($request->filled('state')) {
            $query->where('location_state', $request->state);
        }

        if ($request->filled('city')) {
            $query->where('location_city', $request->city);
        }

        if ($request->boolean('remote')) {
            $query->where('is_remote', true);
        }

        if ($request->filled('employment_type')) {
            $query->where('employment_type', $request->employment_type);
        }

        if ($request->filled('experience_max')) {
            $query->where(function ($q) use ($request) {
                $q->whereNull('experience_min')
                    ->orWhere('experience_min', '<=', $request->float('experience_max'));
            });
        }

        $sortBy = $request->get('sort', 'latest');
        match ($sortBy) {
            'featured' => $query->orderByDesc('is_featured')->latest(),
            'expiring' => $query->orderBy('expires_at'),
            default    => $query->latest(),
        };

        $jobs = $query->paginate($request->integer('per_page', 15));

        return response()->json([
            'success' => true,
            'data'    => $jobs,
        ]);
    }

    public function show(Request $request, JobPosting $job): JsonResponse
    {
        if (! $job->admin_approved || ! $job->is_active) {
            return response()->json(['success' => false, 'message' => 'Job not found.'], 404);
        }

        $job->load(['recruiter.profile', 'specialty']);

        $recruiterProfile = $job->recruiter?->profile;

        return response()->json([
            'success' => true,
            'data'    => [
                'id'                      => $job->id,
                'slug'                    => $job->slug,
                'job_title'               => $job->job_title,
                'job_description'         => $job->job_description,
                'employment_type'         => $job->employment_type?->value,
                'experience_min'          => $job->experience_min,
                'experience_max'          => $job->experience_max,
                'salary_min'              => $job->salary_disclosed ? $job->salary_min : null,
                'salary_max'              => $job->salary_disclosed ? $job->salary_max : null,
                'salary_disclosed'        => $job->salary_disclosed,
                'location_city'           => $job->location_city,
                'location_state'          => $job->location_state,
                'is_remote'               => $job->is_remote,
                'institution_name'        => $job->institution_name,
                'key_skills'              => $job->key_skills,
                'educational_requirements'=> $job->educational_requirements,
                'medical_qualifications'  => $job->medical_qualifications,
                'certifications_required' => $job->certifications_required,
                'specialties'             => $job->specialties,
                'perks_and_benefits'      => $job->perks_and_benefits,
                'number_of_vacancies'     => $job->number_of_vacancies,
                'category_slug'           => $job->category_slug,
                'subcategory_name'        => $job->subcategory_name,
                'specialty'               => $job->specialty?->only(['id', 'name']),
                'expires_at'              => $job->expires_at,
                'is_featured'             => $job->is_featured,
                'thumbnail_url'           => $job->getThumbnailUrl(),
                'contact_name'            => $job->contact_name,
                'contact_email'           => $job->contact_email,
                'contact_phone'           => $job->contact_phone,
                'recruiter'               => $recruiterProfile ? [
                    'institution_name' => $recruiterProfile->institution_name,
                    'city'             => $recruiterProfile->city,
                    'state'            => $recruiterProfile->state,
                    'logo_url'         => $recruiterProfile->logo_path ? asset('storage/' . $recruiterProfile->logo_path) : null,
                    'slug'             => $recruiterProfile->slug,
                ] : null,
                'created_at' => $job->created_at,
            ],
        ]);
    }
}
