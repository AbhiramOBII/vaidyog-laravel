<?php

namespace App\Http\Controllers\Api\JobSeeker;

use App\Http\Controllers\Controller;
use App\Models\JobSeekerProfile;
use App\Models\Specialty;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        $user = $request->user()->load([
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
        ]);

        $profile = $user->jobSeekerProfile;

        return response()->json([
            'success' => true,
            'data'    => [
                'user'          => [
                    'id'    => $user->id,
                    'name'  => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                ],
                'profile'       => $profile ? $this->formatProfile($profile) : null,
                'education'     => $user->educations,
                'employment'    => $user->employments,
                'certifications'=> $user->certifications,
                'languages'     => $user->languages,
                'projects'      => $user->projects,
                'publications'  => $user->publications,
                'presentations' => $user->presentations,
                'research'      => $user->researches,
                'honours_awards'=> $user->honoursAwards,
                'affiliations'  => $user->affiliations,
            ],
        ]);
    }

    public function update(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'salutation'           => ['nullable', 'in:Mr,Ms,Mrs,Dr,Prof'],
            'first_name'           => ['required', 'string', 'max:100'],
            'last_name'            => ['nullable', 'string', 'max:100'],
            'gender'               => ['nullable', 'in:male,female,other,prefer_not_to_say'],
            'date_of_birth'        => ['nullable', 'date', 'before:today'],
            'phone'                => ['nullable', 'string', 'max:20'],
            'email'                => ['nullable', 'email', 'max:255'],
            'country'              => ['nullable', 'string', 'max:100'],
            'state'                => ['nullable', 'string', 'max:100'],
            'city'                 => ['nullable', 'string', 'max:100'],
            'pincode'              => ['nullable', 'string', 'max:20'],
            'nationality'          => ['nullable', 'string', 'max:100'],
            'designation'          => ['nullable', 'string', 'max:150'],
            'subdesignation'       => ['nullable', 'string', 'max:150'],
            'specialty_id'         => ['nullable', 'exists:specialties,id'],
            'category_slug'        => ['nullable', 'string', 'max:100'],
            'subcategory_name'     => ['nullable', 'string', 'max:150'],
            'about'                => ['nullable', 'string', 'max:2000'],
            'experience_years'     => ['nullable', 'numeric', 'min:0', 'max:60'],
            'current_employer'     => ['nullable', 'string', 'max:255'],
            'highest_qualification'=> ['nullable', 'string', 'max:255'],
            'key_skills'           => ['nullable', 'array', 'max:30'],
            'key_skills.*'         => ['string', 'max:80'],
            'is_open_to_work'      => ['boolean'],
        ]);

        $user    = $request->user();
        $profile = $user->jobSeekerProfile ?? new JobSeekerProfile(['user_id' => $user->id]);

        $profile->fill($validated);

        if (! $profile->profile_slug) {
            $profile->profile_slug = $profile->generateProfileSlug();
        }

        $profile->save();

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully.',
            'data'    => $this->formatProfile($profile->fresh()),
        ]);
    }

    public function updatePhoto(Request $request): JsonResponse
    {
        $request->validate([
            'photo' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $user    = $request->user();
        $profile = $user->jobSeekerProfile;

        if (! $profile) {
            return response()->json(['success' => false, 'message' => 'Profile not found. Create profile first.'], 404);
        }

        if ($profile->profile_photo_path) {
            Storage::disk('public')->delete($profile->profile_photo_path);
        }

        $path = $request->file('photo')->store('profile-photos', 'public');
        $profile->update(['profile_photo_path' => $path]);

        return response()->json([
            'success'   => true,
            'message'   => 'Photo updated successfully.',
            'photo_url' => $profile->getProfilePictureUrl(),
        ]);
    }

    public function updateResume(Request $request): JsonResponse
    {
        $request->validate([
            'resume' => ['required', 'file', 'mimes:pdf,doc,docx', 'max:5120'],
        ]);

        $user    = $request->user();
        $profile = $user->jobSeekerProfile;

        if (! $profile) {
            return response()->json(['success' => false, 'message' => 'Profile not found. Create profile first.'], 404);
        }

        if ($profile->resume_path) {
            Storage::disk('public')->delete($profile->resume_path);
        }

        $path = $request->file('resume')->store('resumes', 'public');
        $profile->update(['resume_path' => $path]);

        return response()->json([
            'success'    => true,
            'message'    => 'Resume updated successfully.',
            'resume_url' => $profile->getResumeUrl(),
        ]);
    }

    private function formatProfile(JobSeekerProfile $profile): array
    {
        return [
            'id'                    => $profile->id,
            'salutation'            => $profile->salutation,
            'first_name'            => $profile->first_name,
            'last_name'             => $profile->last_name,
            'full_name'             => $profile->getFullName(),
            'profile_slug'          => $profile->profile_slug,
            'gender'                => $profile->gender,
            'date_of_birth'         => $profile->date_of_birth?->toDateString(),
            'phone'                 => $profile->phone,
            'email'                 => $profile->email,
            'country'               => $profile->country,
            'state'                 => $profile->state,
            'city'                  => $profile->city,
            'pincode'               => $profile->pincode,
            'nationality'           => $profile->nationality,
            'designation'           => $profile->designation,
            'subdesignation'        => $profile->subdesignation,
            'specialty_id'          => $profile->specialty_id,
            'specialty'             => $profile->specialty?->only(['id', 'name']),
            'category_slug'         => $profile->category_slug,
            'subcategory_name'      => $profile->subcategory_name,
            'about'                 => $profile->about,
            'experience_years'      => $profile->experience_years,
            'current_employer'      => $profile->current_employer,
            'highest_qualification' => $profile->highest_qualification,
            'key_skills'            => $profile->key_skills,
            'is_open_to_work'       => $profile->is_open_to_work,
            'profile_completeness'  => $profile->profile_completeness,
            'completeness_label'    => $profile->getCompletenessLabel(),
            'total_experience'      => $profile->getTotalExperience(),
            'profile_photo_url'     => $profile->getProfilePictureUrl(),
            'resume_url'            => $profile->getResumeUrl(),
            'profile_url'           => $profile->profile_slug ? route('profile.public', $profile->profile_slug) : null,
        ];
    }
}
