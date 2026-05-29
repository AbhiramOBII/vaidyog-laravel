@section('title', $profile->institution_name . ' — Healthcare Jobs')
@section('description', Str::limit(strip_tags($profile->description ?? ''), 160))

@php
    $socials = collect([
        'social_linkedin'  => ['LinkedIn', 'M19 3a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h14m-.5 15.5v-5.3a3.26 3.26 0 00-3.26-3.26c-.85 0-1.84.52-2.32 1.3v-1.11h-2.79v8.37h2.79v-4.93c0-.77.62-1.4 1.39-1.4a1.4 1.4 0 011.4 1.4v4.93h2.79M6.88 8.56a1.68 1.68 0 001.68-1.68c0-.93-.75-1.69-1.68-1.69a1.69 1.69 0 00-1.69 1.69c0 .93.76 1.68 1.69 1.68m1.39 9.94v-8.37H5.5v8.37h2.77z'],
        'social_facebook'  => ['Facebook', 'M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z'],
        'social_instagram' => ['Instagram', 'M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z'],
        'social_twitter'   => ['X', 'M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z'],
        'social_youtube'   => ['YouTube', 'M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z'],
    ])->filter(fn ($v, $k) => $profile->$k);
@endphp

<div>
    {{-- Hero Banner --}}
    <section class="py-12 md:py-16 text-white relative overflow-hidden" style="background: linear-gradient(146deg, rgba(70,77,121,1) 26%, rgba(74,176,152,1) 100%);">
        <div class="absolute top-0 right-0 w-96 h-96 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/3"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-white/5 rounded-full translate-y-1/2 -translate-x-1/4"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="flex flex-col lg:flex-row items-center lg:items-start gap-8">
                {{-- Logo --}}
                <div class="shrink-0">
                    @if($profile->logo_path)
                        <div class="w-28 h-28 lg:w-32 lg:h-32 rounded-2xl bg-white shadow-xl shadow-black/10 p-3 flex items-center justify-center">
                            <img src="{{ asset('storage/' . $profile->logo_path) }}" alt="{{ $profile->institution_name }}" class="max-w-full max-h-full object-contain"/>
                        </div>
                    @else
                        <div class="w-28 h-28 lg:w-32 lg:h-32 rounded-2xl bg-white/10 backdrop-blur-sm border border-white/20 flex items-center justify-center shadow-xl">
                            <span class="text-4xl font-bold text-white/80">{{ strtoupper(substr($profile->institution_name, 0, 2)) }}</span>
                        </div>
                    @endif
                </div>

                {{-- Info --}}
                <div class="flex-1 min-w-0 text-center lg:text-left">
                    <h1 class="text-3xl md:text-4xl font-bold leading-tight">{{ $profile->institution_name }}</h1>

                    <div class="flex flex-wrap items-center justify-center lg:justify-start gap-3 mt-3">
                        @if($profile->med_type)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/15 text-white/90 text-xs font-semibold">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                {{ $profile->med_type->label() }}
                            </span>
                        @endif
                        @if($profile->city || $profile->state)
                            <span class="inline-flex items-center gap-1.5 text-sm text-white/80">
                                <svg class="w-4 h-4 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                {{ collect([$profile->city, $profile->state])->filter()->implode(', ') }}
                            </span>
                        @endif
                        @if($profile->website_url)
                            <a href="{{ $profile->website_url }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 text-sm text-white/80 hover:text-white transition-colors">
                                <svg class="w-4 h-4 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                                {{ parse_url($profile->website_url, PHP_URL_HOST) }}
                            </a>
                        @endif
                    </div>

                    {{-- Social links --}}
                    @if($socials->isNotEmpty())
                        <div class="flex items-center justify-center lg:justify-start gap-2 mt-4">
                            @foreach($socials as $key => [$label, $path])
                                <a href="{{ $profile->$key }}" target="_blank" rel="noopener" aria-label="{{ $label }}" class="w-8 h-8 rounded-full bg-white/10 border border-white/20 flex items-center justify-center text-white/70 hover:text-white hover:bg-white/20 transition-all">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="{{ $path }}"/></svg>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Stats --}}
                <div class="shrink-0">
                    <div class="bg-white/10 backdrop-blur-sm border border-white/20 rounded-2xl px-6 py-5 text-center min-w-[110px]">
                        <div class="text-3xl font-bold text-white">{{ $this->totalJobs }}</div>
                        <div class="text-xs text-white/60 mt-1 font-medium uppercase tracking-wide">Open Jobs</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Main Content --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-14">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- Left Column --}}
            <div class="lg:col-span-2 space-y-8">

                {{-- About --}}
                @if($profile->description)
                <div class="bg-white rounded-2xl border border-neutral-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-neutral-100 bg-neutral-50/50">
                        <h2 class="text-base font-semibold text-neutral-900 flex items-center gap-2">
                            <span class="w-8 h-8 rounded-lg bg-[#464d79]/10 flex items-center justify-center">
                                <svg class="w-4 h-4 text-[#464d79]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </span>
                            About {{ $profile->institution_name }}
                        </h2>
                    </div>
                    <div class="px-6 py-5">
                        <div class="prose prose-sm prose-neutral max-w-none text-neutral-600 leading-relaxed [&>p]:mb-3 [&>ul]:list-disc [&>ul]:pl-5 [&>ol]:list-decimal [&>ol]:pl-5">
                            {!! $profile->description !!}
                        </div>
                    </div>
                </div>
                @endif

                {{-- Open Positions --}}
                <div>
                    <div class="flex items-center justify-between mb-5">
                        <h2 class="text-lg font-semibold text-neutral-900 flex items-center gap-2">
                            <span class="w-8 h-8 rounded-lg bg-[#464d79]/10 flex items-center justify-center">
                                <svg class="w-4 h-4 text-[#464d79]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </span>
                            Open Positions
                        </h2>
                        <span class="text-xs font-semibold text-[#464d79] bg-[#464d79]/10 px-3 py-1 rounded-full">{{ $this->totalJobs }} {{ Str::plural('job', $this->totalJobs) }}</span>
                    </div>

                    @if($this->activeJobs->isEmpty())
                        <div class="bg-neutral-50 rounded-2xl border border-neutral-200 border-dashed p-10 text-center">
                            <div class="w-16 h-16 rounded-full bg-neutral-100 flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-neutral-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <p class="text-neutral-500 text-sm font-medium">No open positions at the moment</p>
                            <p class="text-neutral-400 text-xs mt-1">Check back later for new openings.</p>
                        </div>
                    @else
                        <div class="space-y-3">
                            @foreach($this->activeJobs as $job)
                                <a href="{{ route('jobs.show', $job) }}" class="block bg-white rounded-xl border border-neutral-200 p-5 hover:border-[#464d79]/40 hover:shadow-lg hover:shadow-[#464d79]/5 transition-all duration-200 group">
                                    <div class="flex items-start justify-between gap-4">
                                        <div class="min-w-0 flex-1">
                                            <h3 class="font-semibold text-neutral-900 group-hover:text-[#464d79] transition-colors text-[15px]">{{ $job->job_title }}</h3>
                                            <div class="flex flex-wrap items-center gap-x-4 gap-y-1.5 mt-2.5 text-xs text-neutral-500">
                                                @if($job->location_city || $job->location_state)
                                                    <span class="inline-flex items-center gap-1">
                                                        <svg class="w-3.5 h-3.5 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                                        {{ collect([$job->location_city, $job->location_state])->filter()->implode(', ') }}
                                                    </span>
                                                @endif
                                                @if($job->employment_type)
                                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-neutral-100 text-neutral-600 font-medium">
                                                        {{ $job->employment_type->label() }}
                                                    </span>
                                                @endif
                                                @if($job->experience_min || $job->experience_max)
                                                    <span class="inline-flex items-center gap-1">
                                                        <svg class="w-3.5 h-3.5 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                        {{ $job->experience_min }}–{{ $job->experience_max }} yrs
                                                    </span>
                                                @endif
                                            </div>
                                            @if($job->salary_disclosed && $job->salary_min)
                                                <div class="mt-2.5">
                                                    <span class="inline-flex items-center gap-1 text-sm font-semibold text-teal-600">
                                                        ₹{{ number_format($job->salary_min) }} – ₹{{ number_format($job->salary_max) }}
                                                        <span class="text-xs font-normal text-neutral-400">/year</span>
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="shrink-0 w-8 h-8 rounded-full bg-neutral-50 group-hover:bg-[#464d79]/10 flex items-center justify-center transition-colors mt-1">
                                            <svg class="w-4 h-4 text-neutral-300 group-hover:text-[#464d79] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>

                        @if($this->totalJobs > 10)
                            <div class="mt-6 text-center">
                                <a href="{{ route('jobs.index', ['institution' => $profile->institution_name]) }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg border border-[#464d79]/20 text-sm font-semibold text-[#464d79] hover:bg-[#464d79]/5 transition-colors">
                                    View all {{ $this->totalJobs }} jobs
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                                </a>
                            </div>
                        @endif
                    @endif
                </div>
            </div>

            {{-- Right Sidebar --}}
            <div class="space-y-6">

                {{-- Institution Details Card --}}
                <div class="bg-white rounded-2xl border border-neutral-200 overflow-hidden">
                    <div class="px-5 py-4 border-b border-neutral-100 bg-neutral-50/50">
                        <h3 class="text-sm font-semibold text-neutral-900">Institution Details</h3>
                    </div>
                    <div class="p-5">
                        <dl class="space-y-4 text-sm">
                            @if($profile->med_type)
                            <div class="flex items-start gap-3">
                                <dt class="shrink-0 w-8 h-8 rounded-lg bg-[#464d79]/10 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-[#464d79]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                </dt>
                                <dd>
                                    <span class="text-xs text-neutral-400 font-medium uppercase tracking-wide">Type</span>
                                    <p class="text-neutral-800 font-medium mt-0.5">{{ $profile->med_type->label() }}</p>
                                </dd>
                            </div>
                            @endif

                            @if($profile->city || $profile->state)
                            <div class="flex items-start gap-3">
                                <dt class="shrink-0 w-8 h-8 rounded-lg bg-teal-50 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </dt>
                                <dd>
                                    <span class="text-xs text-neutral-400 font-medium uppercase tracking-wide">Location</span>
                                    <p class="text-neutral-800 font-medium mt-0.5">{{ collect([$profile->address_line1, $profile->address_line2])->filter()->implode(', ') }}</p>
                                    <p class="text-neutral-500 text-xs mt-0.5">{{ collect([$profile->city, $profile->state, $profile->pincode])->filter()->implode(', ') }}</p>
                                </dd>
                            </div>
                            @endif

                            @if($profile->website_url)
                            <div class="flex items-start gap-3">
                                <dt class="shrink-0 w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9"/></svg>
                                </dt>
                                <dd>
                                    <span class="text-xs text-neutral-400 font-medium uppercase tracking-wide">Website</span>
                                    <p class="mt-0.5"><a href="{{ $profile->website_url }}" target="_blank" rel="noopener" class="text-[#464d79] font-medium hover:underline">{{ parse_url($profile->website_url, PHP_URL_HOST) }}</a></p>
                                </dd>
                            </div>
                            @endif

                        </dl>
                    </div>
                </div>

                {{-- CTA Card --}}
                <div class="rounded-2xl overflow-hidden" style="background: linear-gradient(135deg, #464d79 0%, #48B098 100%);">
                    <div class="p-6 text-center">
                        <div class="w-12 h-12 rounded-full bg-white/15 flex items-center justify-center mx-auto mb-4">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <h3 class="text-lg font-bold text-white mb-1">Want to work here?</h3>
                        <p class="text-sm text-white/70 mb-5">Explore open positions and start your application today.</p>
                        <a href="{{ route('jobs.index', ['institution' => $profile->institution_name]) }}" class="inline-flex items-center gap-2 px-6 py-3 bg-white text-[#464d79] rounded-xl text-sm font-bold hover:bg-white/90 transition-colors shadow-lg shadow-black/10">
                            View All Jobs
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </a>
                    </div>
                </div>

                {{-- Social Follow --}}
                @if($socials->isNotEmpty())
                <div class="bg-white rounded-2xl border border-neutral-200 p-5">
                    <h3 class="text-sm font-semibold text-neutral-900 mb-3">Follow Us</h3>
                    <div class="flex items-center gap-2">
                        @foreach($socials as $key => [$label, $path])
                            <a href="{{ $profile->$key }}" target="_blank" rel="noopener" aria-label="{{ $label }}" class="w-10 h-10 rounded-xl bg-neutral-50 border border-neutral-200 flex items-center justify-center text-neutral-500 hover:text-[#464d79] hover:border-[#464d79]/30 hover:bg-[#464d79]/5 transition-all">
                                <svg class="w-4.5 h-4.5" fill="currentColor" viewBox="0 0 24 24"><path d="{{ $path }}"/></svg>
                            </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
