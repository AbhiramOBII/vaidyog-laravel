<div class="max-w-5xl mx-auto">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Sidebar --}}
        <div class="space-y-6">
            <div class="bg-white rounded-xl border border-neutral-200 p-6 text-center">
                <img src="{{ $profile->getProfilePictureUrl() }}" class="w-24 h-24 rounded-full mx-auto object-cover border-2 border-neutral-200 mb-4" alt="{{ $profile->getFullName() }}">
                <h1 class="text-lg font-bold text-neutral-900">{{ $profile->getFullName() }}</h1>
                @if ($profile->designation)
                    <p class="text-sm text-[#464d79] font-medium mt-1">{{ $profile->designation }}</p>
                    @if ($profile->subdesignation)<p class="text-xs text-neutral-500">{{ $profile->subdesignation }}</p>@endif
                @endif
                @if ($profile->city || $profile->state)
                    <p class="text-xs text-neutral-400 mt-2">{{ implode(', ', array_filter([$profile->city, $profile->state])) }}</p>
                @endif
                <p class="text-sm text-neutral-600 mt-2 font-medium">{{ $profile->getTotalExperience() }}</p>
                <span class="inline-flex items-center gap-1 mt-3 px-3 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-full">
                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span> Open to Work
                </span>
            </div>

            @if ($profile->getResumeUrl())
                <a href="{{ $profile->getResumeUrl() }}" target="_blank" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium text-white bg-[#464d79] rounded-lg hover:bg-[#3a4166] transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Download Resume
                </a>
            @endif

            @if ($languages->count())
                <div class="bg-white rounded-xl border border-neutral-200 p-5">
                    <h3 class="text-sm font-semibold text-neutral-700 mb-3">Languages</h3>
                    <div class="space-y-2">
                        @foreach ($languages as $lang)
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-neutral-800">{{ $lang->name }}</span>
                                <span class="text-xs text-neutral-400">{{ ucfirst($lang->proficiency) }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">
            @if ($profile->about)
                <div class="bg-white rounded-xl border border-neutral-200 p-6">
                    <h2 class="text-lg font-semibold text-neutral-900 mb-3">About</h2>
                    <p class="text-sm text-neutral-600 leading-relaxed whitespace-pre-line">{{ $profile->about }}</p>
                </div>
            @endif

            @if ($profile->key_skills && count($profile->key_skills))
                <div class="bg-white rounded-xl border border-neutral-200 p-6">
                    <h2 class="text-lg font-semibold text-neutral-900 mb-3">Skills</h2>
                    <div class="flex flex-wrap gap-2">
                        @foreach ($profile->key_skills as $skill)
                            <span class="px-3 py-1.5 text-sm font-medium text-[#4ab098] bg-[#4ab098]/10 rounded-full">{{ $skill }}</span>
                        @endforeach
                    </div>
                </div>
            @endif

            @if ($employments->count())
                <div class="bg-white rounded-xl border border-neutral-200 p-6">
                    <h2 class="text-lg font-semibold text-neutral-900 mb-4">Work Experience</h2>
                    <div class="space-y-4">
                        @foreach ($employments as $emp)
                            <div class="border-l-2 border-[#464d79]/20 pl-4">
                                <h4 class="text-sm font-semibold text-neutral-900">{{ $emp->job_title }}</h4>
                                <p class="text-xs text-neutral-600">{{ $emp->company_name }}</p>
                                <p class="text-xs text-neutral-400 mt-0.5">{{ $emp->getDurationLabel() }} &middot; {{ str_replace('_', ' ', ucfirst($emp->employment_type)) }}</p>
                                @if ($emp->responsibilities)<p class="text-xs text-neutral-500 mt-1.5">{{ Str::limit($emp->responsibilities, 200) }}</p>@endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if ($educations->count())
                <div class="bg-white rounded-xl border border-neutral-200 p-6">
                    <h2 class="text-lg font-semibold text-neutral-900 mb-4">Education</h2>
                    <div class="space-y-4">
                        @foreach ($educations as $edu)
                            <div class="border-l-2 border-[#4ab098]/20 pl-4">
                                <h4 class="text-sm font-semibold text-neutral-900">{{ $edu->degree }}</h4>
                                <p class="text-xs text-neutral-600">{{ $edu->university }}</p>
                                <p class="text-xs text-neutral-400 mt-0.5">{{ $edu->getDurationLabel() }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if ($certifications->count())
                <div class="bg-white rounded-xl border border-neutral-200 p-6">
                    <h2 class="text-lg font-semibold text-neutral-900 mb-4">Certifications</h2>
                    <div class="space-y-3">
                        @foreach ($certifications as $cert)
                            <div class="flex items-start gap-3">
                                <div class="w-2 h-2 bg-[#464d79] rounded-full mt-1.5 shrink-0"></div>
                                <div>
                                    <h4 class="text-sm font-medium text-neutral-900">{{ $cert->name }}</h4>
                                    @if ($cert->medical_institute)<p class="text-xs text-neutral-500">{{ $cert->medical_institute }}</p>@endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if ($projects->count())
                <div class="bg-white rounded-xl border border-neutral-200 p-6">
                    <h2 class="text-lg font-semibold text-neutral-900 mb-4">Projects</h2>
                    <div class="space-y-3">
                        @foreach ($projects as $proj)
                            <div class="p-3 bg-neutral-50 rounded-lg">
                                <h4 class="text-sm font-medium text-neutral-900">{{ $proj->title }}</h4>
                                @if ($proj->client_name)<p class="text-xs text-neutral-500">{{ $proj->client_name }}</p>@endif
                                @if ($proj->details)<p class="text-xs text-neutral-600 mt-1">{{ Str::limit($proj->details, 150) }}</p>@endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
