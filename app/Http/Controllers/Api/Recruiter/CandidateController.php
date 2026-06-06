<?php

namespace App\Http\Controllers\Api\Recruiter;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CandidateController extends Controller
{
    public function show(Request $request, string $userId): JsonResponse
    {
        $candidate = User::where('id', $userId)
            ->where('user_type', 'user')
            ->with([
                'jobSeekerProfile.specialty',
                'educations',
                'employments',
                'certifications',
                'languages',
                'projects',
                'publications',
                'presentations',
                'researches',
                'honoursAwards',
                'affiliations',
            ])
            ->firstOrFail();

        $profile = $candidate->jobSeekerProfile;

        return response()->json([
            'success' => true,
            'data'    => [
                'id'            => $candidate->id,
                'name'          => $candidate->name,
                'email'         => $candidate->email,
                'profile'       => $profile ? [
                    'full_name'          => $profile->getFullName(),
                    'salutation'         => $profile->salutation,
                    'designation'        => $profile->designation,
                    'subdesignation'     => $profile->subdesignation,
                    'specialty'          => $profile->specialty?->only(['id', 'name']),
                    'category_slug'      => $profile->category_slug,
                    'subcategory_name'   => $profile->subcategory_name,
                    'about'              => $profile->about,
                    'experience_years'   => $profile->experience_years,
                    'total_experience'   => $profile->getTotalExperience(),
                    'current_employer'   => $profile->current_employer,
                    'highest_qualification' => $profile->highest_qualification,
                    'key_skills'         => $profile->key_skills,
                    'city'               => $profile->city,
                    'state'              => $profile->state,
                    'country'            => $profile->country,
                    'nationality'        => $profile->nationality,
                    'is_open_to_work'    => $profile->is_open_to_work,
                    'profile_photo_url'  => $profile->getProfilePictureUrl(),
                    'profile_url'        => $profile->profile_slug ? route('profile.public', $profile->profile_slug) : null,
                    'resume_url'         => $profile->getResumeUrl(),
                ] : null,
                'education'     => $candidate->educations,
                'employment'    => $candidate->employments,
                'certifications'=> $candidate->certifications,
                'languages'     => $candidate->languages,
                'projects'      => $candidate->projects,
                'publications'  => $candidate->publications,
                'presentations' => $candidate->presentations,
                'research'      => $candidate->researches,
                'honours_awards'=> $candidate->honoursAwards,
                'affiliations'  => $candidate->affiliations,
            ],
        ]);
    }
}
