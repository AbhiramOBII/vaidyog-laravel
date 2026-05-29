<?php

namespace App\Models;

use App\Enums\MedTypeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class MedicalInstitutionProfile extends Model
{
    protected $fillable = [
        'user_id',
        'institution_name',
        'slug',
        'industry_type',
        'med_type',
        'contact_person_name',
        'contact_person_email',
        'contact_person_phone',
        'description',
        'logo_path',
        'banner_image_path',
        'employee_count',
        'specialties',
        'accreditations',
        'address_line1',
        'address_line2',
        'city',
        'state',
        'pincode',
        'website_url',
        'social_facebook',
        'social_twitter',
        'social_linkedin',
        'social_instagram',
        'social_youtube',
        'additional_information',
        'referral_code',
        'is_featured',
        'featured_at',
        'is_profile_completed',
        'created_by_admin_id',
        'active_plan_snapshot',
    ];

    protected function casts(): array
    {
        return [
            'med_type' => MedTypeEnum::class,
            'specialties' => 'array',
            'accreditations' => 'array',
            'employee_count' => 'integer',
            'is_featured' => 'boolean',
            'is_profile_completed' => 'boolean',
            'featured_at' => 'datetime',
            'active_plan_snapshot' => 'array',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected static function booted(): void
    {
        static::saving(function (self $profile) {
            if (\Illuminate\Support\Facades\Schema::hasColumn('medical_institution_profiles', 'slug')) {
                if (!$profile->slug || $profile->isDirty('institution_name', 'city')) {
                    $profile->slug = static::generateUniqueSlug(
                        $profile->institution_name,
                        $profile->city,
                        $profile->id
                    );
                }
            }
        });
    }

    public static function generateUniqueSlug(string $name, ?string $city, ?int $excludeId = null): string
    {
        $base = Str::slug($name . ' ' . ($city ?? ''));
        $slug = $base;
        $i = 1;

        while (
            static::where('slug', $slug)
                ->when($excludeId, fn ($q) => $q->where('id', '!=', $excludeId))
                ->exists()
        ) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(MedicalInstitution::class, 'user_id');
    }

    public function createdByAdmin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_admin_id');
    }
}
