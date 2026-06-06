<div>
    {{-- Status Banner --}}
    @php $st = $job->getDisplayStatus(); $color = $job->getDisplayStatusColor(); @endphp
    <div class="mb-6 p-4 rounded-xl border
        @if($color==='green') bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800
        @elseif($color==='amber') bg-amber-50 dark:bg-amber-900/20 border-amber-200 dark:border-amber-800
        @elseif($color==='red') bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800
        @else bg-neutral-50 dark:bg-neutral-800 border-neutral-200 dark:border-neutral-700
        @endif">
        <div class="flex items-center justify-between">
            <div>
                <span class="text-sm font-semibold @if($color==='green') text-green-700 dark:text-green-400 @elseif($color==='amber') text-amber-700 dark:text-amber-400 @elseif($color==='red') text-red-700 dark:text-red-400 @else text-neutral-600 dark:text-neutral-400 @endif">{{ $st }}</span>
                @if($job->expires_at)
                <span class="text-xs text-neutral-500 ml-2">Expires {{ $job->expires_at->format('M d, Y') }}</span>
                @endif
            </div>
            @if($job->is_featured)<span class="text-amber-500 text-sm">&#9733; Featured</span>@endif
        </div>
        @if($job->rejection_reason)
        <p class="mt-2 text-sm text-red-600 dark:text-red-400"><strong>Rejection reason:</strong> {{ $job->rejection_reason }}</p>
        @endif
    </div>

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">{{ $job->job_title }}</h1>
            <p class="text-sm text-neutral-500 mt-1">{{ $job->institution_name }} &middot; {{ $job->employment_type->label() }} &middot; {{ $job->number_of_vacancies }} {{ Str::plural('vacancy', $job->number_of_vacancies) }}</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('recruiter.applications.for-job', $job) }}" wire:navigate class="h-9 px-4 inline-flex items-center gap-1.5 rounded-lg text-sm font-medium bg-[#464d79] text-white hover:bg-[#464d79]/90 transition-colors">Applicants</a>
            <a href="{{ route('recruiter.jobs.edit', $job) }}" wire:navigate class="h-9 px-4 inline-flex items-center gap-1.5 rounded-lg text-sm font-medium bg-neutral-100 dark:bg-neutral-700 text-neutral-700 dark:text-neutral-300 hover:bg-neutral-200 dark:hover:bg-neutral-600 transition-colors">Edit</a>
            <button wire:click="toggleActive" class="h-9 px-4 rounded-lg text-sm font-medium {{ $job->is_active ? 'bg-amber-100 text-amber-700 hover:bg-amber-200' : 'bg-green-100 text-green-700 hover:bg-green-200' }} transition-colors">{{ $job->is_active ? 'Disable' : 'Enable' }}</button>
            <button wire:click="deleteJob" wire:confirm="Move this job to bin?" class="h-9 px-4 rounded-lg text-sm font-medium bg-red-100 text-red-700 hover:bg-red-200 transition-colors">Delete</button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main content --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Description --}}
            <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
                <h3 class="text-sm font-semibold text-neutral-500 uppercase tracking-wider mb-3">Job Description</h3>
                <div class="prose prose-sm dark:prose-invert max-w-none text-neutral-700 dark:text-neutral-300">{!! $job->job_description !!}</div>
            </div>

            {{-- Skills & Qualifications --}}
            <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 space-y-4">
                @if($job->key_skills)
                <div><h4 class="text-xs font-semibold text-neutral-500 uppercase mb-2">Key Skills</h4><div class="flex flex-wrap gap-1.5">@foreach($job->key_skills as $s)<span class="px-2.5 py-1 bg-[#464d79]/10 text-[#464d79] dark:text-indigo-300 rounded-lg text-xs">{{ $s }}</span>@endforeach</div></div>
                @endif
                @if($job->medical_qualifications)
                <div><h4 class="text-xs font-semibold text-neutral-500 uppercase mb-2">Medical Qualifications</h4><div class="flex flex-wrap gap-1.5">@foreach($job->medical_qualifications as $s)<span class="px-2.5 py-1 bg-[#4ab098]/10 text-[#4ab098] rounded-lg text-xs">{{ $s }}</span>@endforeach</div></div>
                @endif
                @if($job->specialties)
                <div><h4 class="text-xs font-semibold text-neutral-500 uppercase mb-2">Specialties</h4><div class="flex flex-wrap gap-1.5">@foreach($job->specialties as $s)<span class="px-2.5 py-1 bg-neutral-100 dark:bg-neutral-700 text-neutral-600 dark:text-neutral-300 rounded-lg text-xs">{{ $s }}</span>@endforeach</div></div>
                @endif
                @if($job->certifications_required)
                <div><h4 class="text-xs font-semibold text-neutral-500 uppercase mb-2">Certifications Required</h4><div class="flex flex-wrap gap-1.5">@foreach($job->certifications_required as $s)<span class="px-2.5 py-1 bg-neutral-100 dark:bg-neutral-700 text-neutral-600 dark:text-neutral-300 rounded-lg text-xs">{{ $s }}</span>@endforeach</div></div>
                @endif
                @if($job->educational_requirements)
                <div><h4 class="text-xs font-semibold text-neutral-500 uppercase mb-2">Education</h4><div class="flex flex-wrap gap-1.5">@foreach($job->educational_requirements as $s)<span class="px-2.5 py-1 bg-neutral-100 dark:bg-neutral-700 text-neutral-600 dark:text-neutral-300 rounded-lg text-xs">{{ $s }}</span>@endforeach</div></div>
                @endif
                @if($job->perks_and_benefits)
                <div><h4 class="text-xs font-semibold text-neutral-500 uppercase mb-2">Perks & Benefits</h4><div class="flex flex-wrap gap-1.5">@foreach($job->perks_and_benefits as $s)<span class="px-2.5 py-1 bg-[#4ab098]/10 text-[#4ab098] rounded-lg text-xs">{{ $s }}</span>@endforeach</div></div>
                @endif
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Overview --}}
            <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-5 space-y-3">
                <h3 class="text-sm font-semibold text-neutral-500 uppercase tracking-wider mb-2">Overview</h3>
                <div class="text-sm"><span class="text-neutral-500">Category:</span> <span class="text-neutral-900 dark:text-white font-medium">{{ $job->category_slug }}</span></div>
                <div class="text-sm"><span class="text-neutral-500">Sub-category:</span> <span class="text-neutral-900 dark:text-white font-medium">{{ $job->subcategory_name }}</span></div>
                @if($job->specialty)
                <div class="text-sm"><span class="text-neutral-500">Specialty:</span> <span class="text-neutral-900 dark:text-white font-medium">{{ $job->specialty->name }}</span></div>
                @endif
                <div class="text-sm"><span class="text-neutral-500">Experience:</span> <span class="text-neutral-900 dark:text-white font-medium">{{ $job->experience_min ?? '0' }} - {{ $job->experience_max ?? 'Any' }} years</span></div>
                <div class="text-sm"><span class="text-neutral-500">Salary:</span> <span class="text-neutral-900 dark:text-white font-medium">@if($job->salary_disclosed && $job->salary_min)&#8377;{{ number_format($job->salary_min) }} - &#8377;{{ number_format($job->salary_max) }}@else As per industry norms @endif</span></div>
                <div class="text-sm"><span class="text-neutral-500">Duration:</span> <span class="text-neutral-900 dark:text-white font-medium">{{ $job->posting_duration_days }} days</span></div>
                <div class="text-sm"><span class="text-neutral-500">Posted:</span> <span class="text-neutral-900 dark:text-white font-medium">{{ $job->created_at->format('M d, Y') }}</span></div>
            </div>

            {{-- Location --}}
            <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-5">
                <h3 class="text-sm font-semibold text-neutral-500 uppercase tracking-wider mb-2">Location</h3>
                @if($job->is_remote)<p class="text-sm text-[#4ab098] font-medium">Remote</p>@endif
                @if($job->location_city || $job->location_state)<p class="text-sm text-neutral-700 dark:text-neutral-300">{{ collect([$job->location_city, $job->location_state])->filter()->join(', ') }}</p>@endif
                @if($job->location_office_address)<p class="text-xs text-neutral-500 mt-1">{{ $job->location_office_address }}</p>@endif
                @if($job->location_pincode)<p class="text-xs text-neutral-500">PIN: {{ $job->location_pincode }}</p>@endif
            </div>

            {{-- Contact --}}
            <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-5">
                <h3 class="text-sm font-semibold text-neutral-500 uppercase tracking-wider mb-2">Contact</h3>
                @if($job->contact_name)<p class="text-sm text-neutral-700 dark:text-neutral-300">{{ $job->contact_name }}</p>@endif
                @if($job->contact_email)<p class="text-xs text-neutral-500">{{ $job->contact_email }}</p>@endif
                @if($job->contact_phone)<p class="text-xs text-neutral-500">{{ $job->contact_phone }}</p>@endif
            </div>

            {{-- Applications --}}
            <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-5">
                <h3 class="text-sm font-semibold text-neutral-500 uppercase tracking-wider mb-2">Applications</h3>
                <p class="text-2xl font-bold text-neutral-900 dark:text-white">{{ $job->applications_count ?? $job->applications()->count() }}</p>
                <a href="{{ route('recruiter.applications.for-job', $job) }}" wire:navigate class="text-xs text-[#464d79] hover:underline mt-1 inline-block">View all →</a>
            </div>
        </div>
    </div>

    {{-- Matched Candidates Section --}}
    <div class="mt-8">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-[#464d79]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.455 2.456L21.75 6l-1.036.259a3.375 3.375 0 00-2.455 2.456z"/></svg>
                <h2 class="text-lg font-bold text-neutral-900 dark:text-white">AI-Matched Candidates</h2>
                <span class="text-xs bg-[#464d79]/10 text-[#464d79] px-2 py-0.5 rounded-full font-medium">{{ $matchedCandidates->count() }} found</span>
            </div>
        </div>

        @if($matchedCandidates->isEmpty())
            <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-8 text-center">
                <div class="w-16 h-16 rounded-full bg-neutral-100 dark:bg-neutral-700 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-neutral-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
                </div>
                <h3 class="text-base font-semibold text-neutral-700 dark:text-neutral-300 mb-1">No matching candidates yet</h3>
                <p class="text-sm text-neutral-500 max-w-md mx-auto">There are currently no job seekers whose profile matches this role's requirements. As new candidates register, matches will appear here.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                @foreach($matchedCandidates as $candidate)
                <a href="{{ route('recruiter.candidates.show', $candidate->user_id) }}" wire:navigate class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-4 hover:border-[#464d79]/40 hover:shadow-md transition-all group">
                    <div class="flex items-start gap-3">
                        {{-- Avatar --}}
                        <div class="shrink-0">
                            @if($candidate->profile_photo_path)
                                <img src="{{ Storage::url($candidate->profile_photo_path) }}" class="w-10 h-10 rounded-full object-cover border border-neutral-200">
                            @else
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#464d79] to-[#4ab098] flex items-center justify-center text-white text-sm font-bold">
                                    {{ strtoupper(substr($candidate->first_name, 0, 1)) }}{{ strtoupper(substr($candidate->last_name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        {{-- Info --}}
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-neutral-900 dark:text-white truncate group-hover:text-[#464d79] transition-colors">{{ $candidate->first_name }} {{ $candidate->last_name }}</p>
                            @if($candidate->designation)
                            <p class="text-xs text-neutral-500 truncate">{{ $candidate->designation }}</p>
                            @endif
                            <div class="flex items-center gap-2 mt-1.5 flex-wrap">
                                @if($candidate->experience_years)
                                <span class="text-[10px] px-1.5 py-0.5 bg-neutral-100 dark:bg-neutral-700 text-neutral-600 dark:text-neutral-300 rounded">{{ $candidate->experience_years }} yrs exp</span>
                                @endif
                                @if($candidate->city || $candidate->state)
                                <span class="text-[10px] px-1.5 py-0.5 bg-neutral-100 dark:bg-neutral-700 text-neutral-600 dark:text-neutral-300 rounded">{{ collect([$candidate->city, $candidate->state])->filter()->first() }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    {{-- Matching skills --}}
                    @if(!empty($candidate->key_skills) && !empty($job->key_skills))
                        @php $matched = array_intersect(array_map('strtolower', $candidate->key_skills), array_map('strtolower', $job->key_skills)); @endphp
                        @if(count($matched) > 0)
                        <div class="mt-3 flex flex-wrap gap-1">
                            @foreach(array_slice($matched, 0, 3) as $skill)
                            <span class="text-[10px] px-1.5 py-0.5 bg-[#4ab098]/10 text-[#4ab098] rounded font-medium">{{ $skill }}</span>
                            @endforeach
                            @if(count($matched) > 3)
                            <span class="text-[10px] px-1.5 py-0.5 text-neutral-400">+{{ count($matched) - 3 }} more</span>
                            @endif
                        </div>
                        @endif
                    @endif
                </a>
                @endforeach
            </div>
        @endif
    </div>
</div>
