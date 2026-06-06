<?php

namespace App\Http\Controllers\Api\Recruiter;

use App\Enums\EmploymentTypeEnum;
use App\Http\Controllers\Controller;
use App\Models\JobPosting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class JobController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $recruiterId = $request->user()->id;
        $query       = JobPosting::forRecruiter($recruiterId)
            ->withCount('applications');

        if ($request->filled('status')) {
            match ($request->status) {
                'live'     => $query->approved()->active()->notExpired(),
                'pending'  => $query->pending(),
                'rejected' => $query->rejected(),
                'expired'  => $query->expired(),
                default    => null,
            };
        }

        if ($request->filled('search')) {
            $query->where('job_title', 'like', '%' . $request->search . '%');
        }

        $jobs = $query->latest()->paginate($request->integer('per_page', 15));

        return response()->json([
            'success' => true,
            'data'    => $jobs,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $this->validateJobData($request);

        $recruiter = $request->user();
        $profile   = $recruiter->profile;

        $job = DB::transaction(function () use ($validated, $recruiter, $profile) {
            return JobPosting::create([
                'recruiter_id'           => $recruiter->id,
                'job_title'              => $validated['job_title'],
                'job_description'        => $validated['job_description'],
                'key_skills'             => $validated['key_skills'] ?? null,
                'employment_type'        => $validated['employment_type'],
                'experience_min'         => $validated['experience_min'] ?? null,
                'experience_max'         => $validated['experience_max'] ?? null,
                'location_city'          => $validated['location_city'] ?? null,
                'location_state'         => $validated['location_state'] ?? null,
                'location_office_address'=> $validated['location_office_address'] ?? null,
                'location_pincode'       => $validated['location_pincode'] ?? null,
                'is_remote'              => $validated['is_remote'] ?? false,
                'salary_min'             => ($validated['salary_disclosed'] ?? true) ? ($validated['salary_min'] ?? null) : null,
                'salary_max'             => ($validated['salary_disclosed'] ?? true) ? ($validated['salary_max'] ?? null) : null,
                'salary_disclosed'       => $validated['salary_disclosed'] ?? true,
                'institution_name'       => $profile?->institution_name ?? $recruiter->name,
                'contact_name'           => $validated['contact_name'] ?? null,
                'contact_email'          => $validated['contact_email'] ?? null,
                'contact_phone'          => $validated['contact_phone'] ?? null,
                'educational_requirements' => $validated['educational_requirements'] ?? null,
                'medical_qualifications' => $validated['medical_qualifications'] ?? null,
                'certifications_required'=> $validated['certifications_required'] ?? null,
                'specialties'            => $validated['specialties'] ?? null,
                'perks_and_benefits'     => $validated['perks_and_benefits'] ?? null,
                'posting_duration_days'  => $validated['posting_duration_days'],
                'number_of_vacancies'    => $validated['number_of_vacancies'] ?? 1,
                'category_slug'          => $validated['category_slug'],
                'subcategory_name'       => $validated['subcategory_name'],
                'specialty_id'           => $validated['specialty_id'] ?? null,
                'admin_approved'         => false,
                'is_active'              => true,
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Job posted successfully. Pending admin approval.',
            'data'    => $job,
        ], 201);
    }

    public function show(Request $request, JobPosting $job): JsonResponse
    {
        if ($job->recruiter_id !== $request->user()->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }

        $job->load(['specialty', 'applications']);
        $job->loadCount('applications');

        return response()->json([
            'success' => true,
            'data'    => array_merge($job->toArray(), [
                'display_status'       => $job->getDisplayStatus(),
                'display_status_color' => $job->getDisplayStatusColor(),
                'thumbnail_url'        => $job->getThumbnailUrl(),
            ]),
        ]);
    }

    public function update(Request $request, JobPosting $job): JsonResponse
    {
        if ($job->recruiter_id !== $request->user()->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }

        $validated = $this->validateJobData($request);
        $job->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Job updated successfully.',
            'data'    => $job->fresh(),
        ]);
    }

    public function destroy(Request $request, JobPosting $job): JsonResponse
    {
        if ($job->recruiter_id !== $request->user()->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 403);
        }

        $job->delete();

        return response()->json([
            'success' => true,
            'message' => 'Job deleted successfully.',
        ]);
    }

    private function validateJobData(Request $request): array
    {
        return $request->validate([
            'job_title'               => ['required', 'string', 'max:150'],
            'employment_type'         => ['required', Rule::in(array_column(EmploymentTypeEnum::cases(), 'value'))],
            'number_of_vacancies'     => ['nullable', 'integer', 'min:1', 'max:500'],
            'category_slug'           => ['required', 'exists:job_categories,slug'],
            'subcategory_name'        => ['required', 'string', 'max:150'],
            'specialty_id'            => ['nullable', 'exists:specialties,id'],
            'experience_min'          => ['nullable', 'numeric', 'min:0', 'max:50'],
            'experience_max'          => ['nullable', 'numeric', 'min:0', 'max:50'],
            'salary_disclosed'        => ['nullable', 'boolean'],
            'salary_min'              => ['nullable', 'integer', 'min:0'],
            'salary_max'              => ['nullable', 'integer', 'min:0'],
            'posting_duration_days'   => ['required', Rule::in([15, 30, 60, 90])],
            'job_description'         => ['required', 'string', 'min:100'],
            'key_skills'              => ['nullable', 'array', 'max:20'],
            'key_skills.*'            => ['string', 'max:80'],
            'educational_requirements'=> ['nullable', 'array'],
            'educational_requirements.*' => ['string', 'max:150'],
            'medical_qualifications'  => ['nullable', 'array'],
            'medical_qualifications.*'=> ['string', 'max:150'],
            'certifications_required' => ['nullable', 'array'],
            'certifications_required.*'=> ['string', 'max:150'],
            'specialties'             => ['nullable', 'array'],
            'specialties.*'           => ['string', 'max:150'],
            'perks_and_benefits'      => ['nullable', 'array'],
            'perks_and_benefits.*'    => ['string', 'max:150'],
            'location_city'           => ['nullable', 'string', 'max:100'],
            'location_state'          => ['nullable', 'string', 'max:100'],
            'location_office_address' => ['nullable', 'string', 'max:500'],
            'location_pincode'        => ['nullable', 'string', 'max:20'],
            'is_remote'               => ['nullable', 'boolean'],
            'contact_name'            => ['nullable', 'string', 'max:255'],
            'contact_email'           => ['nullable', 'email', 'max:255'],
            'contact_phone'           => ['nullable', 'string', 'max:20'],
        ]);
    }
}
