@section('title', $job->job_title . ' at ' . ($job->institution_name ?? 'Vaidyog'))
@section('description', Str::limit(strip_tags($job->job_description ?? ''), 155))

@push('schema')
    @include('partials.schema.job-posting', ['job' => $job])
    @include('partials.schema.breadcrumb', ['breadcrumbs' => [
        ['name' => 'Home', 'url' => url('/')],
        ['name' => 'Healthcare Jobs', 'url' => route('jobs.index')],
        ['name' => $job->job_title, 'url' => route('jobs.show', $job)],
    ]])
@endpush

<div class="max-w-4xl mx-auto px-4 py-8">
    {{-- Back link --}}
    <a href="{{ route('jobs.index') }}" wire:navigate class="inline-flex items-center gap-1.5 text-sm text-[#464d79] hover:underline mb-6">&larr; All Jobs</a>

    {{-- Header --}}
    <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 mb-6">
        <div class="flex items-start gap-4">
            <img src="{{ $job->getThumbnailUrl() }}" alt="{{ $job->job_title }}" class="w-16 h-16 rounded-xl object-cover shrink-0"/>
            <div class="flex-1">
                <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">{{ $job->job_title }}</h1>
                <p class="text-sm text-neutral-500 mt-1">{{ $job->institution_name }}</p>
                <div class="flex flex-wrap items-center gap-2 mt-3">
                    <span class="text-xs px-2.5 py-1 rounded-full bg-[#464d79]/10 text-[#464d79] dark:text-indigo-300 font-medium">{{ $job->employment_type->label() }}</span>
                    @if($job->location_city)<span class="text-xs text-neutral-500">{{ collect([$job->location_city, $job->location_state])->filter()->join(', ') }}</span>@endif
                    @if($job->is_remote)<span class="text-xs px-2.5 py-1 rounded-full bg-[#4ab098]/10 text-[#4ab098] font-medium">Remote</span>@endif
                    @if($job->is_featured)<span class="text-xs px-2.5 py-1 rounded-full bg-amber-100 text-amber-700 font-medium">&#9733; Featured</span>@endif
                </div>
            </div>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-6 pt-4 border-t border-neutral-100 dark:border-neutral-700 text-sm">
            <div><span class="text-neutral-500 block text-xs">Vacancies</span><span class="font-semibold text-neutral-900 dark:text-white">{{ $job->number_of_vacancies }}</span></div>
            <div><span class="text-neutral-500 block text-xs">Experience</span><span class="font-semibold text-neutral-900 dark:text-white">{{ $job->experience_min ?? '0' }} - {{ $job->experience_max ?? 'Any' }} years</span></div>
            <div><span class="text-neutral-500 block text-xs">Salary</span><span class="font-semibold text-neutral-900 dark:text-white">@if($job->salary_disclosed && $job->salary_min)&#8377;{{ number_format($job->salary_min) }} - &#8377;{{ number_format($job->salary_max) }}@else As per industry norms @endif</span></div>
            <div><span class="text-neutral-500 block text-xs">Posted</span><span class="font-semibold text-neutral-900 dark:text-white">{{ $job->created_at->diffForHumans() }}</span></div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            {{-- Description --}}
            <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
                <h2 class="text-sm font-semibold text-neutral-500 uppercase tracking-wider mb-3">Job Description</h2>
                <div class="prose prose-sm dark:prose-invert max-w-none text-neutral-700 dark:text-neutral-300">{!! $job->job_description !!}</div>
            </div>

            {{-- Skills & Requirements --}}
            <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 space-y-4">
                @if($job->key_skills)<div><h3 class="text-xs font-semibold text-neutral-500 uppercase mb-2">Key Skills</h3><div class="flex flex-wrap gap-1.5">@foreach($job->key_skills as $s)<span class="px-2.5 py-1 bg-[#464d79]/10 text-[#464d79] dark:text-indigo-300 rounded-lg text-xs">{{ $s }}</span>@endforeach</div></div>@endif
                @if($job->medical_qualifications)<div><h3 class="text-xs font-semibold text-neutral-500 uppercase mb-2">Medical Qualifications</h3><div class="flex flex-wrap gap-1.5">@foreach($job->medical_qualifications as $s)<span class="px-2.5 py-1 bg-[#4ab098]/10 text-[#4ab098] rounded-lg text-xs">{{ $s }}</span>@endforeach</div></div>@endif
                @if($job->specialties)<div><h3 class="text-xs font-semibold text-neutral-500 uppercase mb-2">Specialties</h3><div class="flex flex-wrap gap-1.5">@foreach($job->specialties as $s)<span class="px-2.5 py-1 bg-neutral-100 dark:bg-neutral-700 rounded-lg text-xs">{{ $s }}</span>@endforeach</div></div>@endif
                @if($job->certifications_required)<div><h3 class="text-xs font-semibold text-neutral-500 uppercase mb-2">Certifications Required</h3><div class="flex flex-wrap gap-1.5">@foreach($job->certifications_required as $s)<span class="px-2.5 py-1 bg-neutral-100 dark:bg-neutral-700 rounded-lg text-xs">{{ $s }}</span>@endforeach</div></div>@endif
                @if($job->educational_requirements)<div><h3 class="text-xs font-semibold text-neutral-500 uppercase mb-2">Education</h3><div class="flex flex-wrap gap-1.5">@foreach($job->educational_requirements as $s)<span class="px-2.5 py-1 bg-neutral-100 dark:bg-neutral-700 rounded-lg text-xs">{{ $s }}</span>@endforeach</div></div>@endif
                @if($job->perks_and_benefits)<div><h3 class="text-xs font-semibold text-neutral-500 uppercase mb-2">Perks & Benefits</h3><div class="flex flex-wrap gap-1.5">@foreach($job->perks_and_benefits as $s)<span class="px-2.5 py-1 bg-[#4ab098]/10 text-[#4ab098] rounded-lg text-xs">{{ $s }}</span>@endforeach</div></div>@endif
            </div>
        </div>

        <div class="space-y-6">
            {{-- Apply CTA --}}
            <div class="bg-[#464d79] rounded-xl p-6 text-center">
                @if($this->isOwnJob)
                    {{-- Recruiter viewing own job — hide apply --}}
                @elseif($this->isExpired)
                    <p class="text-white/80 text-sm mb-3">This job is no longer accepting applications.</p>
                    <button disabled class="w-full h-11 bg-white/20 text-white font-semibold rounded-lg text-sm cursor-not-allowed">Expired</button>
                @elseif($this->hasApplied)
                    <p class="text-white text-sm font-medium mb-3">You've applied to this position</p>
                    <div class="w-full h-11 bg-white/20 text-white font-semibold rounded-lg text-sm flex items-center justify-center gap-2">
                        <svg class="w-4 h-4 text-green-300" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        Applied &#10003;
                    </div>
                @elseif(!auth()->check())
                    <p class="text-white text-sm font-medium mb-3">Interested in this position?</p>
                    <a href="{{ route('jobseeker.login', ['return_url' => url()->current()]) }}" class="block w-full h-11 bg-white text-[#464d79] font-semibold rounded-lg text-sm leading-[2.75rem] text-center hover:bg-neutral-100 transition-colors">Apply Now</a>
                @else
                    <p class="text-white text-sm font-medium mb-3">Interested in this position?</p>
                    <button wire:click="$set('showApplyModal', true)" class="w-full h-11 bg-white text-[#464d79] font-semibold rounded-lg text-sm hover:bg-neutral-100 transition-colors">Apply Now</button>
                @endif

                {{-- Save button --}}
                @if(!$this->isOwnJob)
                <button wire:click="toggleSave" class="mt-3 w-full h-9 flex items-center justify-center gap-2 text-sm font-medium rounded-lg transition-colors {{ $this->isSaved ? 'bg-white/20 text-white' : 'bg-transparent border border-white/30 text-white/80 hover:bg-white/10' }}">
                    @if($this->isSaved)
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z"/></svg>
                    Saved
                    @else
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
                    Save Job
                    @endif
                </button>
                @endif
            </div>

            {{-- Apply Modal --}}
            @if($showApplyModal)
            <div x-data="{ open: true }" x-show="open" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4" @close-modal.window="open = false; $wire.set('showApplyModal', false)">
                <div class="fixed inset-0 bg-black/50" @click="open = false; $wire.set('showApplyModal', false)"></div>
                <div class="relative w-full max-w-lg bg-white dark:bg-neutral-800 rounded-2xl shadow-xl p-6 max-h-[90vh] overflow-y-auto">
                    <button @click="open = false; $wire.set('showApplyModal', false)" class="absolute top-4 right-4 text-neutral-400 hover:text-neutral-600">&times;</button>
                    @livewire('job-seeker.applications.apply-modal', ['jobId' => $job->id], key('apply-' . $job->id))
                </div>
            </div>
            @endif

            {{-- Contact --}}
            <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-5">
                <h3 class="text-sm font-semibold text-neutral-500 uppercase tracking-wider mb-3">Contact</h3>
                @if($job->contact_name)<p class="text-sm text-neutral-700 dark:text-neutral-300 font-medium">{{ $job->contact_name }}</p>@endif
                @if($job->contact_email)<p class="text-xs text-neutral-500 mt-1">{{ $job->contact_email }}</p>@endif
                @if($job->contact_phone)<p class="text-xs text-neutral-500">{{ $job->contact_phone }}</p>@endif
            </div>

            {{-- Location --}}
            <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-5">
                <h3 class="text-sm font-semibold text-neutral-500 uppercase tracking-wider mb-3">Location</h3>
                @if($job->is_remote)<p class="text-sm text-[#4ab098] font-medium mb-1">Remote Position</p>@endif
                @if($job->location_city || $job->location_state)<p class="text-sm text-neutral-700 dark:text-neutral-300">{{ collect([$job->location_city, $job->location_state])->filter()->join(', ') }}</p>@endif
                @if($job->location_office_address)<p class="text-xs text-neutral-500 mt-1">{{ $job->location_office_address }}</p>@endif
                @if($job->location_pincode)<p class="text-xs text-neutral-500">PIN: {{ $job->location_pincode }}</p>@endif
            </div>
        </div>
    </div>
</div>
