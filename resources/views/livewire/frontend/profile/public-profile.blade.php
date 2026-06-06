@php
    $seoName        = $profile->getFullName();
    $seoLocation    = collect([$profile->city, $profile->state])->filter()->join(', ');
    $seoDesignation = $profile->designation;
    $seoTitle       = $seoName
        . ($seoDesignation ? ' — ' . $seoDesignation : '')
        . ($seoLocation    ? ' in ' . $seoLocation   : '')
        . ' | Vaidyog';
    $seoDesc = $profile->about
        ? $seoName . ' — ' . \Illuminate\Support\Str::limit(strip_tags($profile->about), 140, '...')
        : $seoName . ($seoDesignation ? ', ' . $seoDesignation : '') . ($seoLocation ? ' based in ' . $seoLocation : '') . '. View full profile on Vaidyog.';
    $seoImage = $profile->getProfilePictureUrl();
    $seoUrl   = route('profile.public', $profile->profile_slug);
@endphp

@section('title', $seoTitle)
@section('description', $seoDesc)
@section('og_title', $seoTitle)
@section('og_description', $seoDesc)
@section('og_image', $seoImage)

@push('schema')
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Person",
  "name": "{{ $seoName }}",
  "url": "{{ $seoUrl }}",
  "image": "{{ $seoImage }}",
  "jobTitle": "{{ $seoDesignation }}",
  "description": "{{ Str::limit(strip_tags($profile->about ?? ''), 200) }}",
  "address": {
    "@type": "PostalAddress",
    "addressLocality": "{{ $profile->city }}",
    "addressRegion": "{{ $profile->state }}",
    "addressCountry": "{{ $profile->country ?? 'IN' }}"
  }@if($profile->specialty),
  "knowsAbout": "{{ $profile->specialty->name }}"@endif@if(!empty($profile->key_skills)),
  "hasOccupation": {
    "@type": "Occupation",
    "name": "{{ $seoDesignation }}",
    "skills": "{{ implode(', ', $profile->key_skills) }}"
  }@endif
}
</script>
@endpush

<div class="max-w-4xl mx-auto py-8 px-4 sm:px-6">

    {{-- Profile Header / Banner --}}
    <div class="bg-white rounded-xl border border-neutral-200 overflow-hidden mb-6">
        {{-- Banner gradient --}}
        <div class="h-32 sm:h-40 bg-gradient-to-r from-[#464d79] via-[#5a6399] to-[#4ab098] relative">
            <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMjAiIGN5PSIyMCIgcj0iMSIgZmlsbD0icmdiYSgyNTUsMjU1LDI1NSwwLjA1KSIvPjwvc3ZnPg==')] opacity-40"></div>
        </div>

        {{-- Profile info --}}
        <div class="px-6 sm:px-8 pb-6 relative">
            {{-- Avatar --}}
            <div class="-mt-16 sm:-mt-20 mb-4">
                <img src="{{ $profile->getProfilePictureUrl() }}"
                     alt="{{ $profile->getFullName() }}"
                     class="w-28 h-28 sm:w-36 sm:h-36 rounded-full border-4 border-white shadow-lg object-cover bg-white"/>
            </div>

            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-neutral-900">{{ $profile->getFullName() }}</h1>

                    @if($profile->designation)
                        <p class="text-lg text-[#464d79] font-medium mt-1">{{ $profile->designation }}</p>
                        @if($profile->subdesignation)
                            <p class="text-sm text-neutral-500">{{ $profile->subdesignation }}</p>
                        @endif
                    @endif

                    <div class="flex flex-wrap items-center gap-3 mt-3 text-sm text-neutral-600">
                        @if($profile->city || $profile->state)
                            <span class="inline-flex items-center gap-1">
                                <svg class="w-4 h-4 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                {{ collect([$profile->city, $profile->state])->filter()->join(', ') }}
                            </span>
                        @endif

                        @if($profile->experience_years)
                            <span class="inline-flex items-center gap-1">
                                <svg class="w-4 h-4 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                {{ $profile->experience_years }} years experience
                            </span>
                        @endif
                    </div>

                    @if($profile->is_open_to_work)
                        <span class="inline-flex items-center gap-1.5 mt-3 px-3 py-1 rounded-full bg-green-50 border border-green-200 text-green-700 text-xs font-semibold">
                            <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                            Open to Work
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- About --}}
            @if($profile->about)
            <div class="bg-white rounded-xl border border-neutral-200 p-6">
                <h2 class="text-lg font-semibold text-neutral-900 mb-3 flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#464d79]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    About
                </h2>
                <p class="text-neutral-700 leading-relaxed whitespace-pre-line">{{ $profile->about }}</p>
            </div>
            @endif

            {{-- Work Experience --}}
            @if($profile->employments->count())
            <div class="bg-white rounded-xl border border-neutral-200 p-6">
                <h2 class="text-lg font-semibold text-neutral-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#464d79]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    Experience
                </h2>
                <div class="space-y-5">
                    @foreach($profile->employments->sortByDesc('start_date') as $emp)
                    <div class="relative pl-6 border-l-2 border-neutral-200">
                        <div class="absolute left-0 top-1 -translate-x-1/2 w-3 h-3 rounded-full {{ $emp->is_current ? 'bg-[#4ab098]' : 'bg-neutral-300' }} border-2 border-white"></div>
                        <h3 class="font-semibold text-neutral-900">{{ $emp->job_title ?? 'Position' }}</h3>
                        @if($emp->company_name)
                            <p class="text-sm text-[#464d79] font-medium">{{ $emp->company_name }}</p>
                        @endif
                        <p class="text-xs text-neutral-500 mt-1">
                            {{ $emp->start_date ? \Carbon\Carbon::parse($emp->start_date)->format('M Y') : '' }}
                            — {{ $emp->is_current ? 'Present' : ($emp->end_date ? \Carbon\Carbon::parse($emp->end_date)->format('M Y') : '') }}
                        </p>
                        @if($emp->description)
                            <p class="text-sm text-neutral-600 mt-2 leading-relaxed">{{ $emp->description }}</p>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Education --}}
            @if($profile->educations->count())
            <div class="bg-white rounded-xl border border-neutral-200 p-6">
                <h2 class="text-lg font-semibold text-neutral-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#464d79]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                    Education
                </h2>
                <div class="space-y-5">
                    @foreach($profile->educations->sortByDesc('start_year') as $edu)
                    <div class="relative pl-6 border-l-2 border-neutral-200">
                        <div class="absolute left-0 top-1 -translate-x-1/2 w-3 h-3 rounded-full bg-neutral-300 border-2 border-white"></div>
                        <h3 class="font-semibold text-neutral-900">{{ $edu->degree ?? 'Degree' }}</h3>
                        @if($edu->institution)
                            <p class="text-sm text-[#464d79] font-medium">{{ $edu->institution }}</p>
                        @endif
                        @if($edu->field_of_study)
                            <p class="text-sm text-neutral-600">{{ $edu->field_of_study }}</p>
                        @endif
                        <p class="text-xs text-neutral-500 mt-1">
                            {{ $edu->start_year ?? '' }}{{ $edu->end_year ? ' — ' . $edu->end_year : '' }}
                        </p>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Certifications --}}
            @if($profile->certifications->count())
            <div class="bg-white rounded-xl border border-neutral-200 p-6">
                <h2 class="text-lg font-semibold text-neutral-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#464d79]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                    Certifications & Licenses
                </h2>
                <div class="space-y-4">
                    @foreach($profile->certifications as $cert)
                    <div class="flex items-start gap-3 p-3 rounded-lg bg-neutral-50">
                        <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-[#464d79]/10 flex items-center justify-center">
                            <svg class="w-5 h-5 text-[#464d79]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <h3 class="font-medium text-neutral-900 text-sm">{{ $cert->name }}</h3>
                            @if($cert->issuing_organization)
                                <p class="text-xs text-neutral-500">{{ $cert->issuing_organization }}</p>
                            @endif
                            @if($cert->issue_date)
                                <p class="text-xs text-neutral-400 mt-0.5">Issued {{ \Carbon\Carbon::parse($cert->issue_date)->format('M Y') }}</p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">

            {{-- Skills --}}
            @if(!empty($profile->key_skills))
            <div class="bg-white rounded-xl border border-neutral-200 p-6">
                <h2 class="text-base font-semibold text-neutral-900 mb-3 flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#464d79]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    Skills
                </h2>
                <div class="flex flex-wrap gap-2">
                    @foreach($profile->key_skills as $skill)
                        <span class="px-3 py-1.5 text-xs font-medium rounded-full bg-[#464d79]/8 text-[#464d79] border border-[#464d79]/15">{{ $skill }}</span>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Languages --}}
            @if($profile->languages->count())
            <div class="bg-white rounded-xl border border-neutral-200 p-6">
                <h2 class="text-base font-semibold text-neutral-900 mb-3 flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#464d79]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/></svg>
                    Languages
                </h2>
                <div class="space-y-2.5">
                    @foreach($profile->languages as $lang)
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-neutral-800">{{ $lang->name }}</span>
                        <span class="text-xs px-2 py-0.5 rounded-full {{ $lang->proficiency === 'native' ? 'bg-green-100 text-green-700' : ($lang->proficiency === 'fluent' ? 'bg-blue-100 text-blue-700' : 'bg-neutral-100 text-neutral-600') }}">{{ ucfirst($lang->proficiency) }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Quick Info --}}
            <div class="bg-white rounded-xl border border-neutral-200 p-6">
                <h2 class="text-base font-semibold text-neutral-900 mb-3">Details</h2>
                <dl class="space-y-3 text-sm">
                    @if($profile->category_name)
                    <div>
                        <dt class="text-xs text-neutral-500">Category</dt>
                        <dd class="font-medium text-neutral-800">{{ $profile->category_name }}</dd>
                    </div>
                    @endif
                    @if($profile->subcategory_name)
                    <div>
                        <dt class="text-xs text-neutral-500">Sub-category</dt>
                        <dd class="font-medium text-neutral-800">{{ $profile->subcategory_name }}</dd>
                    </div>
                    @endif
                    @if($profile->specialty)
                    <div>
                        <dt class="text-xs text-neutral-500">Specialty</dt>
                        <dd class="font-medium text-neutral-800">{{ $profile->specialty->name }}</dd>
                    </div>
                    @endif
                    @if($profile->highest_qualification)
                    <div>
                        <dt class="text-xs text-neutral-500">Qualification</dt>
                        <dd class="font-medium text-neutral-800">{{ $profile->highest_qualification }}</dd>
                    </div>
                    @endif
                    @if($profile->current_employer)
                    <div>
                        <dt class="text-xs text-neutral-500">Current Employer</dt>
                        <dd class="font-medium text-neutral-800">{{ $profile->current_employer }}</dd>
                    </div>
                    @endif
                </dl>
            </div>
        </div>
    </div>
</div>
