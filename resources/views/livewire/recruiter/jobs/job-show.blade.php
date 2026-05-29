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

            {{-- Applications stub --}}
            <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-5 opacity-60">
                <h3 class="text-sm font-semibold text-neutral-500 uppercase tracking-wider mb-2">Applications</h3>
                <p class="text-2xl font-bold text-neutral-900 dark:text-white">0</p>
                <p class="text-xs text-neutral-400 mt-1">Coming soon</p>
            </div>
        </div>
    </div>
</div>
