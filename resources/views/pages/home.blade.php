@extends('layouts.public')
@section('title', 'Vaidyog')
@section('description', 'India\'s #1 healthcare job portal. Find jobs for doctors, nurses, pharmacists and allied health professionals.')

@section('content')

@push('schema')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'WebPage',
    'name' => 'Vaidyog - Healthcare Jobs India',
    'description' => "India's #1 healthcare job portal. Find jobs for doctors, nurses, pharmacists and allied health professionals.",
    'url' => url('/'),
    'isPartOf' => ['@type' => 'WebSite', 'name' => 'Vaidyog', 'url' => url('/')],
    'about' => ['@type' => 'Thing', 'name' => 'Healthcare Employment', 'description' => 'Connecting healthcare professionals with leading hospitals and clinics across India'],
    'mainEntity' => [
        '@type' => 'ItemList',
        'name' => 'Healthcare Job Categories',
        'itemListElement' => [
            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Doctor Jobs'],
            ['@type' => 'ListItem', 'position' => 2, 'name' => 'Nursing Jobs'],
            ['@type' => 'ListItem', 'position' => 3, 'name' => 'Pharmacy Jobs'],
            ['@type' => 'ListItem', 'position' => 4, 'name' => 'Allied Health Jobs'],
            ['@type' => 'ListItem', 'position' => 5, 'name' => 'Hospital Administration Jobs'],
        ],
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
@endpush
{{-- HERO SECTION --}}
<section class="relative min-h-[480px] flex items-center overflow-hidden">
    {{-- Background image --}}
    <picture class="absolute inset-0 w-full h-full">
        <source media="(max-width: 767px)" srcset="{{ asset('images/bg-vaidyog-hero-mobile.jpg') }}"/>
        <source media="(min-width: 768px)" srcset="{{ asset('images/bg-vaidyog-hero-web.jpg') }}"/>
        <img src="{{ asset('images/bg-vaidyog-hero-web.jpg') }}" alt="" class="absolute inset-0 w-full h-full object-cover" aria-hidden="true"/>
    </picture>
    {{-- Dark overlay for text readability --}}
    <div class="absolute inset-0 bg-[#1a1f3d]/60"></div>

    <div class="relative max-w-5xl mx-auto px-4 sm:px-6 py-16 md:py-20 w-full">
        <p class="text-teal-100 text-sm font-semibold uppercase tracking-wide mb-3">India's #1 Healthcare Job Portal</p>
        <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold text-white leading-tight">Find Your Next Healthcare Role</h1>
        <p class="text-lg text-primary-100 mt-3 max-w-2xl">Connecting doctors, nurses, and allied health professionals with top hospitals and clinics across India.</p>

        {{-- Search bar --}}
        <div class="mt-8">
            <livewire:frontend.search.hero-search />
        </div>

        {{-- Quick stats --}}
        <div class="mt-6 flex flex-wrap items-center gap-4 text-sm text-white/80">
            <span>50,000+ Jobs</span>
            <span class="hidden sm:inline">·</span>
            <span>20,000+ Hospitals</span>
            <span class="hidden sm:inline">·</span>
            <span>1,00,000+ Registered Professionals</span>
        </div>

        {{-- Popular searches --}}
        <div class="mt-5 flex flex-wrap items-center gap-2">
            <span class="text-sm text-white/70">Popular:</span>
            @foreach(['Cardiologist', 'Staff Nurse', 'MBBS Doctor', 'Physiotherapist', 'Radiologist', 'ICU Nurse'] as $term)
            <a href="{{ route('jobs.index', ['search' => $term]) }}" class="border border-white/30 text-white text-xs px-3 py-1 rounded-full hover:bg-white/20 transition-colors">{{ $term }}</a>
            @endforeach
        </div>
    </div>
</section>

{{-- SECTION 1 — FEATURED JOBS --}}
<section class="py-12 md:py-16 bg-white" style="background-image: radial-gradient(circle, #e5e7eb 1px, transparent 1px); background-size: 24px 24px;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Featured Jobs</h2>
                <p class="text-sm text-gray-500 mt-1">Handpicked opportunities from top healthcare employers</p>
            </div>
            <a href="{{ route('jobs.index') }}" class="text-sm font-medium text-teal-500 hover:text-teal-600 hidden sm:inline-flex items-center gap-1">View All Jobs →</a>
        </div>

        @php
            $featuredJobs = \App\Models\JobPosting::publiclyVisible()->where('is_featured', true)->latest('featured_at')->take(6)->get();
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($featuredJobs as $job)
                @include('partials.job-card', ['job' => $job, 'featured' => true])
            @empty
                <p class="text-gray-400 col-span-full text-center py-8">No featured jobs at the moment.</p>
            @endforelse
        </div>

        <div class="mt-6 text-center sm:hidden">
            <a href="{{ route('jobs.index') }}" class="text-sm font-medium text-teal-500">View All Jobs →</a>
        </div>
    </div>
</section>

{{-- LATEST JOBS --}}
<section class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl font-bold text-gray-900">Latest Job Postings</h2>
            <a href="{{ route('jobs.index') }}" class="text-sm font-medium text-teal-500 hover:text-teal-600 hidden sm:block">View All →</a>
        </div>

        @php
            $latestJobs = \App\Models\JobPosting::publiclyVisible()->where('is_featured', false)->latest('approved_at')->take(6)->get();
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($latestJobs as $job)
                @include('partials.job-card', ['job' => $job, 'featured' => false])
            @empty
                <p class="text-gray-400 col-span-full text-center py-8">No jobs posted yet. Check back soon!</p>
            @endforelse
        </div>
    </div>
</section>

{{-- SECTION 2 — BROWSE BY SPECIALTY --}}
<section class="py-16 md:py-20" style="background: linear-gradient(90deg, rgba(237,240,255,1) 0%, rgba(230,255,249,1) 100%)">
    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12">
        <div class="text-center mb-10">
            <h2 class="text-2xl font-bold text-gray-900">Browse Jobs by Specialty</h2>
            <p class="text-sm text-gray-500 mt-1">Find opportunities in your area of expertise</p>
        </div>

        @php
            $specialties = \App\Models\Specialty::active()->featured()->ordered()
                ->whereHas('jobs', fn($q) => $q->publiclyVisible())
                ->withCount(['jobs' => fn($q) => $q->publiclyVisible()])
                ->get();
        @endphp

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @forelse($specialties as $spec)
            <a href="{{ route('jobs.index', ['specialty' => [$spec->id]]) }}" class="bg-gradient-to-br from-[#464d79] to-[#2d3250] rounded-xl p-5 text-center shadow-card hover:shadow-card-hover border border-white/10 transition-all duration-200 hover:-translate-y-0.5">
                @if($spec->icon_path)
                    <img src="{{ asset('storage/' . $spec->icon_path) }}" alt="{{ $spec->name }}" class="w-12 h-12 mx-auto mb-3 object-contain"/>
                @else
                    <div class="w-12 h-12 mx-auto mb-3 bg-white/15 rounded-full flex items-center justify-center text-white text-xl font-bold">{{ substr($spec->name, 0, 1) }}</div>
                @endif
                <div class="font-semibold text-white text-sm">{{ $spec->name }}</div>
                <div class="text-teal-300 text-xs font-medium mt-1">{{ $spec->jobs_count }} {{ Str::plural('Job', $spec->jobs_count) }}</div>
            </a>
            @empty
            <p class="col-span-full text-center text-gray-400 py-8">Specialties coming soon. Manage them in Admin → Specialties.</p>
            @endforelse
        </div>
    </div>
</section>

{{-- SECTION — WHO IS VAIDYOG FOR --}}
<section class="py-16 md:py-24 relative" style="background-color:#f8f9fb;background-image:radial-gradient(circle,#d1d5db 1px,transparent 1px);background-size:24px 24px">
    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12 relative z-10">
        <div class="text-center mb-24">
            <p class="text-sm font-semibold text-teal-500 uppercase tracking-wider mb-3">Built for Healthcare</p>
            <h2 class="text-2xl md:text-3xl font-bold text-gray-900">Who is Vaidyog for?</h2>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">

            {{-- Left: Healthcare Professionals --}}
            <div>
                <h3 class="text-xs font-bold uppercase tracking-widest text-primary-500 mb-8">For Healthcare Professionals</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="group relative bg-white rounded-xl p-5 border border-gray-100 shadow-sm hover:shadow-md hover:border-primary-200 transition-all duration-200">
                        <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-primary-500 to-primary-600 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        </div>
                        <p class="font-bold text-gray-900 text-sm">Doctors & Specialists</p>
                        <p class="text-xs text-gray-500 mt-1">MBBS, MD, MS, DM and super-specialists across all departments.</p>
                    </div>
                    <div class="group relative bg-white rounded-xl p-5 border border-gray-100 shadow-sm hover:shadow-md hover:border-primary-200 transition-all duration-200">
                        <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-primary-500 to-primary-600 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        </div>
                        <p class="font-bold text-gray-900 text-sm">Nurses & Paramedics</p>
                        <p class="text-xs text-gray-500 mt-1">GNM, BSc, MSc nursing professionals and paramedical staff.</p>
                    </div>
                    <div class="group relative bg-white rounded-xl p-5 border border-gray-100 shadow-sm hover:shadow-md hover:border-primary-200 transition-all duration-200">
                        <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-primary-500 to-primary-600 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <p class="font-bold text-gray-900 text-sm">Medical Technicians</p>
                        <p class="text-xs text-gray-500 mt-1">Lab techs, radiology techs, OT assistants and more.</p>
                    </div>
                    <div class="group relative bg-white rounded-xl p-5 border border-gray-100 shadow-sm hover:shadow-md hover:border-primary-200 transition-all duration-200">
                        <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-primary-500 to-primary-600 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <p class="font-bold text-gray-900 text-sm">Healthcare Admins</p>
                        <p class="text-xs text-gray-500 mt-1">Hospital managers, billing, coding and operations staff.</p>
                    </div>
                </div>
            </div>

            {{-- Right: Employers & Institutions --}}
            <div>
                <h3 class="text-xs font-bold uppercase tracking-widest text-teal-500 mb-8">For Employers & Institutions</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="group relative bg-white rounded-xl p-5 border border-gray-100 shadow-sm hover:shadow-md hover:border-teal-200 transition-all duration-200">
                        <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-teal-400 to-teal-500 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/></svg>
                        </div>
                        <p class="font-bold text-gray-900 text-sm">Hospitals & Clinics</p>
                        <p class="text-xs text-gray-500 mt-1">Multi-specialty hospitals, nursing homes and private clinics.</p>
                    </div>
                    <div class="group relative bg-white rounded-xl p-5 border border-gray-100 shadow-sm hover:shadow-md hover:border-teal-200 transition-all duration-200">
                        <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-teal-400 to-teal-500 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                        </div>
                        <p class="font-bold text-gray-900 text-sm">Research Institutes</p>
                        <p class="text-xs text-gray-500 mt-1">Medical colleges, ICMR labs and pharma R&D centres.</p>
                    </div>
                    <div class="group relative bg-white rounded-xl p-5 border border-gray-100 shadow-sm hover:shadow-md hover:border-teal-200 transition-all duration-200">
                        <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-teal-400 to-teal-500 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                        <p class="font-bold text-gray-900 text-sm">Healthcare Startups</p>
                        <p class="text-xs text-gray-500 mt-1">HealthTech, telemedicine and digital health ventures.</p>
                    </div>
                    <div class="group relative bg-white rounded-xl p-5 border border-gray-100 shadow-sm hover:shadow-md hover:border-teal-200 transition-all duration-200">
                        <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-teal-400 to-teal-500 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                        </div>
                        <p class="font-bold text-gray-900 text-sm">Diagnostic Centers</p>
                        <p class="text-xs text-gray-500 mt-1">Pathology labs, imaging centres and wellness diagnostics.</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- SECTION 3 — HOW VAIDYOG WORKS --}}
<section class="py-16 md:py-24 bg-gradient-to-br from-primary-600 via-primary-500 to-teal-400">
    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-12">
        <div class="text-center mb-12">
            <h2 class="text-2xl md:text-3xl font-bold text-white">How Vaidyog Works</h2>
            <p class="text-sm text-primary-100 mt-2">Simple steps to get started — whether you're looking for a job or hiring talent</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-16">
            {{-- For Job Seekers --}}
            <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 md:p-8 border border-white/20">
                <h3 class="text-lg font-bold text-white mb-6">For Job Seekers</h3>
                <ul class="space-y-5">
                    <li class="flex items-start gap-3">
                        <span class="mt-0.5 flex-shrink-0 w-6 h-6 rounded-full bg-primary-500 flex items-center justify-center">
                            <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        </span>
                        <div>
                            <p class="font-semibold text-white">Create Your Profile</p>
                            <p class="text-sm text-primary-100">Sign up and fill in your professional details.</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="mt-0.5 flex-shrink-0 w-6 h-6 rounded-full bg-primary-500 flex items-center justify-center">
                            <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        </span>
                        <div>
                            <p class="font-semibold text-white">Get Matched Instantly</p>
                            <p class="text-sm text-primary-100">AI finds the best jobs based on skills and experience.</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="mt-0.5 flex-shrink-0 w-6 h-6 rounded-full bg-primary-500 flex items-center justify-center">
                            <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        </span>
                        <div>
                            <p class="font-semibold text-white">Apply with One Click</p>
                            <p class="text-sm text-primary-100">No lengthy forms, just instant applications.</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="mt-0.5 flex-shrink-0 w-6 h-6 rounded-full bg-primary-500 flex items-center justify-center">
                            <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        </span>
                        <div>
                            <p class="font-semibold text-white">Land Your Dream Job</p>
                            <p class="text-sm text-primary-100">Get hired by verified institutions faster.</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="mt-0.5 flex-shrink-0 w-6 h-6 rounded-full bg-primary-500 flex items-center justify-center">
                            <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        </span>
                        <div>
                            <p class="font-semibold text-white">Healthcare News & Events</p>
                            <p class="text-sm text-primary-100">Stay updated on medical news and events.</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="mt-0.5 flex-shrink-0 w-6 h-6 rounded-full bg-primary-500 flex items-center justify-center">
                            <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        </span>
                        <div>
                            <p class="font-semibold text-white">Community Engagement</p>
                            <p class="text-sm text-primary-100">Connect, discuss, and grow your network.</p>
                        </div>
                    </li>
                </ul>
            </div>

            {{-- For Recruiters --}}
            <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 md:p-8 border border-white/20">
                <h3 class="text-lg font-bold text-white mb-6">For Recruiters</h3>
                <ul class="space-y-5">
                    <li class="flex items-start gap-3">
                        <span class="mt-0.5 flex-shrink-0 w-6 h-6 rounded-full bg-teal-500 flex items-center justify-center">
                            <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        </span>
                        <div>
                            <p class="font-semibold text-white">Post a Job</p>
                            <p class="text-sm text-primary-100">List job openings in minutes.</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="mt-0.5 flex-shrink-0 w-6 h-6 rounded-full bg-teal-500 flex items-center justify-center">
                            <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        </span>
                        <div>
                            <p class="font-semibold text-white">AI Shortlisting</p>
                            <p class="text-sm text-primary-100">The system recommends top candidates instantly.</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="mt-0.5 flex-shrink-0 w-6 h-6 rounded-full bg-teal-500 flex items-center justify-center">
                            <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        </span>
                        <div>
                            <p class="font-semibold text-white">Effortless Hiring</p>
                            <p class="text-sm text-primary-100">Manage applications and communication with ease.</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="mt-0.5 flex-shrink-0 w-6 h-6 rounded-full bg-teal-500 flex items-center justify-center">
                            <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        </span>
                        <div>
                            <p class="font-semibold text-white">Make the Right Hire</p>
                            <p class="text-sm text-primary-100">Find high-quality professionals quickly.</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="mt-0.5 flex-shrink-0 w-6 h-6 rounded-full bg-teal-500 flex items-center justify-center">
                            <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        </span>
                        <div>
                            <p class="font-semibold text-white">Healthcare News & Events</p>
                            <p class="text-sm text-primary-100">Explore medical trends insights and events.</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="mt-0.5 flex-shrink-0 w-6 h-6 rounded-full bg-teal-500 flex items-center justify-center">
                            <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        </span>
                        <div>
                            <p class="font-semibold text-white">Community Engagement</p>
                            <p class="text-sm text-primary-100">Engage, connect, and expand your network.</p>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        <div class="mt-12 flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="{{ route('jobseeker.register') }}" class="px-6 py-3 bg-white text-primary-600 font-semibold text-sm rounded-lg hover:bg-gray-100 transition-colors shadow-lg">Create Free Account</a>
            <a href="{{ route('jobs.index') }}" class="px-6 py-3 border-2 border-white text-white font-semibold text-sm rounded-lg hover:bg-white/10 transition-colors">Browse Jobs</a>
        </div>
    </div>
</section>

{{-- SECTION 4 — FOR RECRUITERS --}}
<section class="py-12 md:py-16 bg-primary-600">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-10 items-center">
            <div class="lg:col-span-3">
                <p class="text-teal-300 uppercase text-sm font-semibold tracking-wide mb-2">For Hospitals & Clinics</p>
                <h2 class="text-3xl font-bold text-white mb-4">Hire Top Healthcare Talent Fast</h2>
                <p class="text-primary-100 mb-6">Post jobs, review ranked applicants, and access India's largest verified healthcare talent pool.</p>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('recruiter.register') }}" class="px-6 py-3 bg-teal-300 text-white font-semibold text-sm rounded-lg hover:bg-teal-400 transition-colors">Post a Job Free</a>
                    <a href="{{ route('plans.index') }}" class="px-6 py-3 border border-white text-white font-semibold text-sm rounded-lg hover:bg-white/10 transition-colors">View Plans</a>
                </div>
            </div>
            <div class="lg:col-span-2">
                <ul class="space-y-3 text-primary-100">
                    <li class="flex items-center gap-3"><svg class="w-5 h-5 text-teal-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>Verified healthcare professionals only</li>
                    <li class="flex items-center gap-3"><svg class="w-5 h-5 text-teal-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>AI-ranked applicants (Platinum → Gold → Silver → Basic)</li>
                    <li class="flex items-center gap-3"><svg class="w-5 h-5 text-teal-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>Resume access and direct messaging</li>
                    <li class="flex items-center gap-3"><svg class="w-5 h-5 text-teal-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>Dedicated recruiter dashboard</li>
                    <li class="flex items-center gap-3"><svg class="w-5 h-5 text-teal-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>Plans from Clinics to Enterprise hospitals</li>
                </ul>
            </div>
        </div>
    </div>
</section>


{{-- SECTION — LATEST BLOGS --}}
@php $latestBlogs = \App\Models\Blog::published()->with('category')->latest('published_at')->take(4)->get(); @endphp
@if($latestBlogs->isNotEmpty())
<section class="py-32 md:py-32 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Latest from Our Blog</h2>
                <p class="text-sm text-gray-500 mt-1">Career tips, industry news, and healthcare insights</p>
            </div>
            <a href="{{ route('blogs.index') }}" class="text-sm font-medium text-teal-500 hover:text-teal-600 hidden sm:inline-flex items-center gap-1">View All Blogs →</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
            @foreach($latestBlogs as $blog)
            <a href="{{ route('blogs.show', $blog->slug) }}" class="bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-lg hover:-translate-y-0.5 transition-all group">
                <div class="aspect-[16/10] bg-gradient-to-br from-[#464d79]/20 to-[#4ab098]/20 relative overflow-hidden">
                    @if($blog->thumbnail_image)
                        <img src="{{ asset('storage/' . $blog->thumbnail_image) }}" alt="{{ $blog->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"/>
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <svg class="w-10 h-10 text-[#464d79]/20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                        </div>
                    @endif
                    @if($blog->category)
                    <span class="absolute top-3 left-3 text-[10px] px-2.5 py-1 rounded-full bg-white/90 text-[#464d79] font-semibold shadow-sm">{{ $blog->category->title }}</span>
                    @endif
                </div>
                <div class="p-4">
                    <h3 class="text-sm font-semibold text-gray-900 line-clamp-2 group-hover:text-[#464d79] transition-colors">{{ $blog->title }}</h3>
                    <p class="text-xs text-gray-400 mt-2">{{ $blog->published_at?->format('M d, Y') }}</p>
                </div>
            </a>
            @endforeach
        </div>

        <div class="mt-6 text-center sm:hidden">
            <a href="{{ route('blogs.index') }}" class="text-sm font-medium text-teal-500">View All Blogs →</a>
        </div>
    </div>
</section>
@endif

{{-- SECTION — MOBILE APP --}}
<section class="py-32 md:py-32 bg-white" style="background: linear-gradient(90deg, rgba(237, 240, 255, 1) 0%, rgba(230, 255, 249, 1) 100%);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">

            {{-- Left: Device mockup placeholder --}}
            <div class="relative flex items-center justify-center">
                <img src="{{ asset('images/mobile-and-web-app.webp') }}" alt="Vaidyog Mobile & Web App" class="w-full max-w-lg mx-auto"/>
            </div>

            {{-- Right: Content --}}
            <div>
                <p class="text-sm font-semibold text-teal-500 uppercase tracking-wider mb-3">Mobile App: Find Jobs & Hire On The Go</p>
                <h2 class="text-2xl md:text-3xl font-bold text-gray-900 leading-tight mb-6">Your Healthcare Job Search, <span class="text-teal-500">Anytime, Anywhere.</span></h2>

                <ul class="space-y-4 mb-8">
                    <li class="flex items-center gap-3">
                        <span class="w-6 h-6 rounded-full bg-gradient-to-br from-[#454E79] to-[#48B098] flex items-center justify-center shrink-0">
                            <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        </span>
                        <span class="text-sm text-gray-700">Instant job alerts</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="w-6 h-6 rounded-full bg-gradient-to-br from-[#454E79] to-[#48B098] flex items-center justify-center shrink-0">
                            <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        </span>
                        <span class="text-sm text-gray-700">One-click apply & hiring</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="w-6 h-6 rounded-full bg-gradient-to-br from-[#454E79] to-[#48B098] flex items-center justify-center shrink-0">
                            <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        </span>
                        <span class="text-sm text-gray-700">Chat directly with recruiters</span>
                    </li>
                </ul>

                {{-- App store buttons --}}
                <div class="flex flex-col sm:flex-row gap-6">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">For Job Seekers</p>
                        <div class="flex gap-2">
                            <a href="#"><img src="{{ asset('images/playstore.svg') }}" alt="Google Play" class="h-11"/></a>
                            <a href="#"><img src="{{ asset('images/istore.svg') }}" alt="App Store" class="h-11"/></a>
                        </div>
                    </div>
                    <div class="sm:border-l sm:border-gray-200 sm:pl-6">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">For Recruiters</p>
                        <div class="flex gap-2">
                            <a href="#"><img src="{{ asset('images/playstore.svg') }}" alt="Google Play" class="h-11"/></a>
                            <a href="#"><img src="{{ asset('images/istore.svg') }}" alt="App Store" class="h-11"/></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- SECTION — FAQs --}}
@php $faq = \App\Models\Faq::first(); @endphp
@if($faq && !empty($faq->items))
<section class="py-16 md:py-20 bg-white" style="background-image: radial-gradient(circle, #e5e7eb 1px, transparent 1px); background-size: 24px 24px;">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10">
            <h2 class="text-2xl font-bold text-gray-900">Frequently Asked Questions</h2>
            <p class="text-sm text-gray-500 mt-1">Got questions? We've got answers.</p>
        </div>

        <div class="space-y-3" x-data="{ open: 0 }">
            @foreach($faq->items as $index => $item)
            <div class="rounded-xl overflow-hidden" style="background: linear-gradient(90deg, rgba(69,78,121,1) 0%, rgba(72,176,152,1) 100%);">
                <button @click="open = open === {{ $index }} ? null : {{ $index }}" class="flex items-center justify-between w-full px-5 py-4 text-left">
                    <span class="text-sm font-semibold text-white">{{ $item['question'] }}</span>
                    <svg :class="open === {{ $index }} ? 'rotate-180' : ''" class="w-4 h-4 text-white/70 transition-transform duration-200 shrink-0 ml-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="open === {{ $index }}" x-collapse>
                    <div class="px-5 pb-4 text-sm text-white/80 leading-relaxed">{{ $item['answer'] }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- SECTION 6 — TRUST STRIP --}}
<section class="py-8 bg-white border-t border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-wrap justify-center gap-8 md:gap-12">
            <div class="flex items-center gap-2 text-sm text-gray-500 font-medium"><span class="text-lg">🏥</span> 500+ Hospitals Onboarded</div>
            <div class="flex items-center gap-2 text-sm text-gray-500 font-medium"><span class="text-lg">👨‍⚕️</span> 1 Lakh+ Professionals</div>
            <div class="flex items-center gap-2 text-sm text-gray-500 font-medium"><span class="text-lg">✅</span> Verified Job Listings</div>
            <div class="flex items-center gap-2 text-sm text-gray-500 font-medium"><span class="text-lg">🔒</span> Secure & Private</div>
            <div class="flex items-center gap-2 text-sm text-gray-500 font-medium"><span class="text-lg">📱</span> Mobile Friendly</div>
        </div>
    </div>
</section>
@endsection
