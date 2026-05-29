{{-- Schema.org JobPosting structured data --}}
@php
    $salaryMin = $job->salary_min ?? null;
    $salaryMax = $job->salary_max ?? null;
    $employmentType = match($job->employment_type?->value ?? $job->employment_type ?? '') {
        'full_time' => 'FULL_TIME',
        'part_time' => 'PART_TIME',
        'contract' => 'CONTRACTOR',
        'internship' => 'INTERN',
        'temporary' => 'TEMPORARY',
        'locum' => 'TEMPORARY',
        default => 'FULL_TIME',
    };

    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'JobPosting',
        'title' => $job->job_title,
        'description' => strip_tags($job->job_description ?? ''),
        'datePosted' => ($job->approved_at ?? $job->created_at)->toW3cString(),
        'employmentType' => $employmentType,
        'hiringOrganization' => [
            '@type' => 'Organization',
            'name' => $job->institution_name ?? 'Vaidyog Healthcare Partner',
            'sameAs' => url('/'),
        ],
        'jobLocation' => [
            '@type' => 'Place',
            'address' => [
                '@type' => 'PostalAddress',
                'addressLocality' => $job->location_city ?? '',
                'addressRegion' => $job->location_state ?? '',
                'addressCountry' => 'IN',
            ],
        ],
        'industry' => 'Healthcare',
        'occupationalCategory' => 'Healthcare',
    ];

    if ($job->expires_at) {
        $schema['validThrough'] = $job->expires_at->toW3cString();
    }

    if ($salaryMin || $salaryMax) {
        $salaryValue = ['@type' => 'QuantitativeValue', 'unitText' => 'YEAR'];
        if ($salaryMin && $salaryMax) {
            $salaryValue['minValue'] = $salaryMin;
            $salaryValue['maxValue'] = $salaryMax;
        } else {
            $salaryValue['value'] = $salaryMin ?: $salaryMax;
        }
        $schema['baseSalary'] = [
            '@type' => 'MonetaryAmount',
            'currency' => $job->salary_currency ?? 'INR',
            'value' => $salaryValue,
        ];
    }

    if ($job->experience_min !== null) {
        $schema['experienceRequirements'] = $job->experience_min . '-' . ($job->experience_max ?? $job->experience_min) . ' years';
    }

    if ($job->educational_requirements) {
        $schema['educationRequirements'] = $job->educational_requirements;
    }
@endphp
<script type="application/ld+json">
{!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
