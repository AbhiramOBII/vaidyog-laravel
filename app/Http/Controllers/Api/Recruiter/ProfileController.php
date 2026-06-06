<?php

namespace App\Http\Controllers\Api\Recruiter;

use App\Http\Controllers\Controller;
use App\Models\MedicalInstitutionProfile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        $recruiter = $request->user()->load('profile');
        $profile   = $recruiter->profile;

        return response()->json([
            'success' => true,
            'data'    => [
                'id'                   => $recruiter->id,
                'name'                 => $recruiter->name,
                'email'                => $recruiter->email,
                'phone'                => $recruiter->phone,
                'is_profile_completed' => $recruiter->is_profile_completed,
                'profile'              => $profile ? $this->formatProfile($profile) : null,
            ],
        ]);
    }

    public function update(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'institution_name'      => ['required', 'string', 'max:255'],
            'med_type'              => ['required', 'string', 'in:hospital,clinic,diagnostic_center,pharmacy,nursing_home,medical_college,research_institute,other'],
            'industry_type'         => ['nullable', 'string', 'max:100'],
            'contact_person_name'   => ['nullable', 'string', 'max:255'],
            'contact_person_email'  => ['nullable', 'email', 'max:255'],
            'contact_person_phone'  => ['nullable', 'string', 'max:20'],
            'description'           => ['nullable', 'string', 'max:3000'],
            'employee_count'        => ['nullable', 'integer', 'min:1'],
            'specialties'           => ['nullable', 'array'],
            'specialties.*'         => ['string', 'max:100'],
            'accreditations'        => ['nullable', 'array'],
            'accreditations.*'      => ['string', 'max:100'],
            'address_line1'         => ['nullable', 'string', 'max:255'],
            'address_line2'         => ['nullable', 'string', 'max:255'],
            'country'               => ['nullable', 'string', 'max:100'],
            'state'                 => ['nullable', 'string', 'max:100'],
            'city'                  => ['nullable', 'string', 'max:100'],
            'pincode'               => ['nullable', 'string', 'max:20'],
            'website_url'           => ['nullable', 'url', 'max:500'],
            'social_facebook'       => ['nullable', 'url', 'max:500'],
            'social_twitter'        => ['nullable', 'url', 'max:500'],
            'social_linkedin'       => ['nullable', 'url', 'max:500'],
            'social_instagram'      => ['nullable', 'url', 'max:500'],
            'social_youtube'        => ['nullable', 'url', 'max:500'],
            'additional_information'=> ['nullable', 'string', 'max:2000'],
        ]);

        $recruiter = $request->user();
        $profile   = $recruiter->profile ?? new MedicalInstitutionProfile(['user_id' => $recruiter->id]);

        $profile->fill($validated);
        $profile->save();

        if (! $recruiter->is_profile_completed) {
            $recruiter->update(['is_profile_completed' => true]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully.',
            'data'    => $this->formatProfile($profile->fresh()),
        ]);
    }

    public function updateLogo(Request $request): JsonResponse
    {
        $request->validate([
            'logo' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $recruiter = $request->user();
        $profile   = $recruiter->profile;

        if (! $profile) {
            return response()->json(['success' => false, 'message' => 'Profile not found. Create profile first.'], 404);
        }

        if ($profile->logo_path) {
            Storage::disk('public')->delete($profile->logo_path);
        }

        $path = $request->file('logo')->store('recruiter-logos', 'public');
        $profile->update(['logo_path' => $path]);

        return response()->json([
            'success'  => true,
            'message'  => 'Logo updated successfully.',
            'logo_url' => asset('storage/' . $path),
        ]);
    }

    private function formatProfile(MedicalInstitutionProfile $profile): array
    {
        return [
            'id'                    => $profile->id,
            'institution_name'      => $profile->institution_name,
            'slug'                  => $profile->slug,
            'med_type'              => $profile->med_type?->value,
            'industry_type'         => $profile->industry_type,
            'contact_person_name'   => $profile->contact_person_name,
            'contact_person_email'  => $profile->contact_person_email,
            'contact_person_phone'  => $profile->contact_person_phone,
            'description'           => $profile->description,
            'employee_count'        => $profile->employee_count,
            'specialties'           => $profile->specialties,
            'accreditations'        => $profile->accreditations,
            'address_line1'         => $profile->address_line1,
            'address_line2'         => $profile->address_line2,
            'country'               => $profile->country,
            'state'                 => $profile->state,
            'city'                  => $profile->city,
            'pincode'               => $profile->pincode,
            'website_url'           => $profile->website_url,
            'social_facebook'       => $profile->social_facebook,
            'social_twitter'        => $profile->social_twitter,
            'social_linkedin'       => $profile->social_linkedin,
            'social_instagram'      => $profile->social_instagram,
            'social_youtube'        => $profile->social_youtube,
            'additional_information'=> $profile->additional_information,
            'logo_url'              => $profile->logo_path ? asset('storage/' . $profile->logo_path) : null,
            'banner_url'            => $profile->banner_image_path ? asset('storage/' . $profile->banner_image_path) : null,
            'referral_code'         => $profile->referral_code,
            'is_profile_completed'  => $profile->is_profile_completed,
        ];
    }
}
