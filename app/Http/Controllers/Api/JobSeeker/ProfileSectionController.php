<?php

namespace App\Http\Controllers\Api\JobSeeker;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfileSectionController extends Controller
{
    private const SECTION_CONFIG = [
        'education' => [
            'model'      => \App\Models\UserEducation::class,
            'relation'   => 'educations',
            'rules'      => [
                'degree'                => ['required', 'string', 'max:255'],
                'university'            => ['required', 'string', 'max:255'],
                'course'                => ['nullable', 'string', 'max:255'],
                'specialization'        => ['nullable', 'string', 'max:255'],
                'course_type'           => ['nullable', 'in:full_time,part_time,distance_learning,correspondence'],
                'course_duration_start' => ['nullable', 'integer', 'min:1900', 'max:2100'],
                'course_duration_end'   => ['nullable', 'integer', 'min:1900', 'max:2100'],
                'grading_system'        => ['nullable', 'in:percentage,cgpa,grade'],
                'grade'                 => ['nullable', 'string', 'max:20'],
            ],
        ],
        'employment' => [
            'model'      => \App\Models\UserEmployment::class,
            'relation'   => 'employments',
            'rules'      => [
                'company_name'              => ['required', 'string', 'max:255'],
                'job_title'                 => ['required', 'string', 'max:255'],
                'employment_type'           => ['nullable', 'string', 'max:50'],
                'is_current'                => ['boolean'],
                'joining_date'              => ['nullable', 'date'],
                'leaving_date'              => ['nullable', 'date', 'after_or_equal:joining_date'],
                'total_experience_months'   => ['nullable', 'integer', 'min:0'],
                'current_salary'            => ['nullable', 'numeric', 'min:0'],
                'salary_currency'           => ['nullable', 'string', 'max:10'],
                'responsibilities'          => ['nullable', 'string', 'max:2000'],
            ],
        ],
        'certification' => [
            'model'      => \App\Models\UserCertification::class,
            'relation'   => 'certifications',
            'rules'      => [
                'name'             => ['required', 'string', 'max:255'],
                'completion_date'  => ['nullable', 'date'],
                'certification_id' => ['nullable', 'string', 'max:100'],
                'certification_url'=> ['nullable', 'url', 'max:500'],
                'medical_institute'=> ['nullable', 'string', 'max:255'],
                'validity_start'   => ['nullable', 'date'],
                'validity_end'     => ['nullable', 'date', 'after_or_equal:validity_start'],
                'no_expiry'        => ['boolean'],
            ],
        ],
        'language' => [
            'model'      => \App\Models\UserLanguage::class,
            'relation'   => 'languages',
            'rules'      => [
                'name'        => ['required', 'string', 'max:100'],
                'proficiency' => ['nullable', 'in:beginner,intermediate,proficient,expert,native'],
                'can_read'    => ['boolean'],
                'can_write'   => ['boolean'],
                'can_speak'   => ['boolean'],
            ],
        ],
        'project' => [
            'model'      => \App\Models\UserProject::class,
            'relation'   => 'projects',
            'rules'      => [
                'title'       => ['required', 'string', 'max:255'],
                'location'    => ['nullable', 'string', 'max:255'],
                'client_name' => ['nullable', 'string', 'max:255'],
                'status'      => ['nullable', 'in:in_progress,finished'],
                'start_date'  => ['nullable', 'date'],
                'end_date'    => ['nullable', 'date'],
                'details'     => ['nullable', 'string', 'max:2000'],
            ],
        ],
        'publication' => [
            'model'      => \App\Models\UserPublication::class,
            'relation'   => 'publications',
            'rules'      => [
                'title'            => ['required', 'string', 'max:255'],
                'publication_name' => ['nullable', 'string', 'max:255'],
                'published_date'   => ['nullable', 'date'],
                'url'              => ['nullable', 'url', 'max:500'],
                'description'      => ['nullable', 'string', 'max:1000'],
            ],
        ],
        'presentation' => [
            'model'      => \App\Models\UserPresentation::class,
            'relation'   => 'presentations',
            'rules'      => [
                'title'       => ['required', 'string', 'max:255'],
                'event_name'  => ['nullable', 'string', 'max:255'],
                'event_date'  => ['nullable', 'date'],
                'location'    => ['nullable', 'string', 'max:255'],
                'description' => ['nullable', 'string', 'max:1000'],
            ],
        ],
        'research' => [
            'model'      => \App\Models\UserResearch::class,
            'relation'   => 'researches',
            'rules'      => [
                'title'          => ['required', 'string', 'max:255'],
                'institution'    => ['nullable', 'string', 'max:255'],
                'published_date' => ['nullable', 'date'],
                'url'            => ['nullable', 'url', 'max:500'],
                'description'    => ['nullable', 'string', 'max:1000'],
            ],
        ],
        'honours_award' => [
            'model'      => \App\Models\UserHonoursAward::class,
            'relation'   => 'honoursAwards',
            'rules'      => [
                'title'       => ['required', 'string', 'max:255'],
                'issuing_body'=> ['nullable', 'string', 'max:255'],
                'award_date'  => ['nullable', 'date'],
                'description' => ['nullable', 'string', 'max:1000'],
            ],
        ],
        'affiliation' => [
            'model'      => \App\Models\UserAffiliation::class,
            'relation'   => 'affiliations',
            'rules'      => [
                'organization_name' => ['required', 'string', 'max:255'],
                'role'              => ['nullable', 'string', 'max:255'],
                'member_since'      => ['nullable', 'date'],
                'member_until'      => ['nullable', 'date'],
                'is_current'        => ['boolean'],
            ],
        ],
    ];

    public function index(Request $request, string $section): JsonResponse
    {
        $config   = $this->resolveSection($section);
        $relation = $config['relation'];
        $items    = $request->user()->{$relation}()->get();

        return response()->json([
            'success' => true,
            'data'    => $items,
        ]);
    }

    public function store(Request $request, string $section): JsonResponse
    {
        $config    = $this->resolveSection($section);
        $validated = $request->validate($config['rules']);
        $validated['user_id'] = $request->user()->id;

        $item = $config['model']::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Entry added successfully.',
            'data'    => $item,
        ], 201);
    }

    public function update(Request $request, string $section, int $id): JsonResponse
    {
        $config = $this->resolveSection($section);
        $item   = $config['model']::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $validated = $request->validate($config['rules']);
        $item->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Entry updated successfully.',
            'data'    => $item->fresh(),
        ]);
    }

    public function destroy(Request $request, string $section, int $id): JsonResponse
    {
        $config = $this->resolveSection($section);
        $item   = $config['model']::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $item->delete();

        return response()->json([
            'success' => true,
            'message' => 'Entry deleted successfully.',
        ]);
    }

    private function resolveSection(string $section): array
    {
        if (! isset(self::SECTION_CONFIG[$section])) {
            abort(404, "Unknown profile section: {$section}");
        }

        return self::SECTION_CONFIG[$section];
    }
}
