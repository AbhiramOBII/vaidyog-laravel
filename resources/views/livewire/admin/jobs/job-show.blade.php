<div>
    @if(session('success'))
    <div class="mb-4 p-3 rounded-lg bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-sm text-green-700 dark:text-green-300">{{ session('success') }}</div>
    @endif

    {{-- Header --}}
    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">{{ $job->job_title }}</h1>
            <div class="flex items-center gap-2 mt-2 flex-wrap">
                @php $st = $job->getDisplayStatus(); $color = $job->getDisplayStatusColor(); @endphp
                <span class="text-xs px-2.5 py-1 rounded-full font-semibold @if($color==='green') bg-green-100 text-green-700 @elseif($color==='amber') bg-amber-100 text-amber-700 @elseif($color==='red') bg-red-100 text-red-700 @else bg-neutral-100 text-neutral-600 @endif">{{ $st }}</span>
                @if($job->is_featured)<span class="text-xs px-2.5 py-1 rounded-full font-semibold bg-amber-100 text-amber-700">&#9733; Featured</span>@endif
                <span class="text-sm text-neutral-500">{{ $job->institution_name }}</span>
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('admin.jobs.edit', $job) }}" wire:navigate class="h-9 px-4 inline-flex items-center rounded-lg text-sm font-medium bg-neutral-100 dark:bg-neutral-700 text-neutral-700 dark:text-neutral-300 hover:bg-neutral-200">Edit</a>
            @if(!$job->admin_approved && !$job->rejection_reason)
            <button wire:click="approve" class="h-9 px-4 rounded-lg text-sm font-medium bg-green-600 text-white hover:bg-green-700 transition-colors">Approve</button>
            <button wire:click="$set('showRejectForm', true)" class="h-9 px-4 rounded-lg text-sm font-medium bg-red-100 text-red-700 hover:bg-red-200 transition-colors">Reject</button>
            @endif
            <button wire:click="toggleFeatured" class="h-9 px-4 rounded-lg text-sm font-medium {{ $job->is_featured ? 'bg-amber-100 text-amber-700' : 'bg-neutral-100 text-neutral-600' }} hover:opacity-80 transition-colors">{{ $job->is_featured ? 'Unfeature' : 'Feature' }}</button>
            <button wire:click="toggleActive" class="h-9 px-4 rounded-lg text-sm font-medium {{ $job->is_active ? 'bg-amber-100 text-amber-700' : 'bg-green-100 text-green-700' }} transition-colors">{{ $job->is_active ? 'Disable' : 'Enable' }}</button>
            <button wire:click="moveToBin" wire:confirm="Move this job to bin?" class="h-9 px-4 rounded-lg text-sm font-medium bg-red-100 text-red-700 hover:bg-red-200 transition-colors">Bin</button>
        </div>
    </div>

    {{-- Reject form --}}
    @if($showRejectForm)
    <div class="mb-6 p-4 rounded-xl bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800">
        <p class="text-sm font-medium text-red-700 dark:text-red-300 mb-2">Provide rejection reason:</p>
        <textarea wire:model="rejectionReason" rows="3" class="w-full px-3 py-2 bg-white dark:bg-neutral-900 border border-red-200 dark:border-red-800 rounded-lg text-sm focus:outline-none focus:border-red-400 mb-2" placeholder="Min 10 characters..."></textarea>
        @error('rejectionReason') <p class="text-xs text-red-500 mb-2">{{ $message }}</p> @enderror
        <div class="flex gap-2">
            <button wire:click="confirmReject" class="h-8 px-4 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold rounded-lg">Reject</button>
            <button wire:click="$set('showRejectForm', false)" class="h-8 px-4 text-xs text-neutral-600 hover:bg-neutral-100 rounded-lg">Cancel</button>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Left column --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Approval status card --}}
            <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
                <h3 class="text-sm font-semibold text-neutral-500 uppercase tracking-wider mb-3">Approval Status</h3>
                <div class="space-y-2 text-sm">
                    <div><span class="text-neutral-500">Status:</span> <span class="font-medium">{{ $st }}</span></div>
                    @if($job->approved_at)<div><span class="text-neutral-500">Approved:</span> <span class="font-medium">{{ $job->approved_at->format('M d, Y H:i') }}</span></div>@endif
                    @if($job->rejection_reason)<div class="p-3 rounded-lg bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-300 text-sm"><strong>Reason:</strong> {{ $job->rejection_reason }}</div>@endif
                    @if($job->expires_at)<div><span class="text-neutral-500">Expires:</span> <span class="font-medium">{{ $job->expires_at->format('M d, Y') }}</span></div>@endif
                </div>
            </div>

            {{-- Job overview --}}
            <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
                <h3 class="text-sm font-semibold text-neutral-500 uppercase tracking-wider mb-3">Job Overview</h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 text-sm">
                    <div><span class="text-neutral-500 block">Type</span><span class="font-medium text-neutral-900 dark:text-white">{{ $job->employment_type->label() }}</span></div>
                    <div><span class="text-neutral-500 block">Vacancies</span><span class="font-medium text-neutral-900 dark:text-white">{{ $job->number_of_vacancies }}</span></div>
                    <div><span class="text-neutral-500 block">Experience</span><span class="font-medium text-neutral-900 dark:text-white">{{ $job->experience_min ?? '0' }} - {{ $job->experience_max ?? 'Any' }} yrs</span></div>
                    <div><span class="text-neutral-500 block">Salary</span><span class="font-medium text-neutral-900 dark:text-white">@if($job->salary_disclosed && $job->salary_min)&#8377;{{ number_format($job->salary_min) }} - &#8377;{{ number_format($job->salary_max) }}@else Not disclosed @endif</span></div>
                    <div><span class="text-neutral-500 block">Duration</span><span class="font-medium text-neutral-900 dark:text-white">{{ $job->posting_duration_days }} days</span></div>
                    <div><span class="text-neutral-500 block">Posted</span><span class="font-medium text-neutral-900 dark:text-white">{{ $job->created_at->format('M d, Y') }}</span></div>
                </div>
            </div>

            {{-- Description --}}
            <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
                <h3 class="text-sm font-semibold text-neutral-500 uppercase tracking-wider mb-3">Description</h3>
                <div class="prose prose-sm dark:prose-invert max-w-none text-neutral-700 dark:text-neutral-300">{!! $job->job_description !!}</div>
            </div>

            {{-- Skills --}}
            <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 space-y-4">
                @if($job->key_skills)<div><h4 class="text-xs font-semibold text-neutral-500 uppercase mb-2">Key Skills</h4><div class="flex flex-wrap gap-1.5">@foreach($job->key_skills as $s)<span class="px-2.5 py-1 bg-[#464d79]/10 text-[#464d79] dark:text-indigo-300 rounded-lg text-xs">{{ $s }}</span>@endforeach</div></div>@endif
                @if($job->medical_qualifications)<div><h4 class="text-xs font-semibold text-neutral-500 uppercase mb-2">Medical Qualifications</h4><div class="flex flex-wrap gap-1.5">@foreach($job->medical_qualifications as $s)<span class="px-2.5 py-1 bg-[#4ab098]/10 text-[#4ab098] rounded-lg text-xs">{{ $s }}</span>@endforeach</div></div>@endif
                @if($job->specialties)<div><h4 class="text-xs font-semibold text-neutral-500 uppercase mb-2">Specialties</h4><div class="flex flex-wrap gap-1.5">@foreach($job->specialties as $s)<span class="px-2.5 py-1 bg-neutral-100 dark:bg-neutral-700 rounded-lg text-xs">{{ $s }}</span>@endforeach</div></div>@endif
                @if($job->certifications_required)<div><h4 class="text-xs font-semibold text-neutral-500 uppercase mb-2">Certifications</h4><div class="flex flex-wrap gap-1.5">@foreach($job->certifications_required as $s)<span class="px-2.5 py-1 bg-neutral-100 dark:bg-neutral-700 rounded-lg text-xs">{{ $s }}</span>@endforeach</div></div>@endif
                @if($job->educational_requirements)<div><h4 class="text-xs font-semibold text-neutral-500 uppercase mb-2">Education</h4><div class="flex flex-wrap gap-1.5">@foreach($job->educational_requirements as $s)<span class="px-2.5 py-1 bg-neutral-100 dark:bg-neutral-700 rounded-lg text-xs">{{ $s }}</span>@endforeach</div></div>@endif
                @if($job->perks_and_benefits)<div><h4 class="text-xs font-semibold text-neutral-500 uppercase mb-2">Perks</h4><div class="flex flex-wrap gap-1.5">@foreach($job->perks_and_benefits as $s)<span class="px-2.5 py-1 bg-[#4ab098]/10 text-[#4ab098] rounded-lg text-xs">{{ $s }}</span>@endforeach</div></div>@endif
            </div>
        </div>

        {{-- Right column --}}
        <div class="space-y-6">
            {{-- Location --}}
            <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-5">
                <h3 class="text-sm font-semibold text-neutral-500 uppercase tracking-wider mb-2">Location</h3>
                @if($job->is_remote)<p class="text-sm text-[#4ab098] font-medium mb-1">Remote</p>@endif
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

            {{-- Recruiter card --}}
            <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-5">
                <h3 class="text-sm font-semibold text-neutral-500 uppercase tracking-wider mb-2">Recruiter</h3>
                <p class="text-sm font-medium text-neutral-900 dark:text-white">{{ $job->institution_name }}</p>
                @if($job->recruiter)<a href="{{ route('admin.recruiters.show', $job->recruiter) }}" wire:navigate class="text-xs text-[#464d79] hover:underline mt-1 inline-block">View recruiter profile &rarr;</a>@endif
            </div>

            {{-- Applications stub --}}
            <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-5 opacity-60">
                <h3 class="text-sm font-semibold text-neutral-500 uppercase tracking-wider mb-2">Applications</h3>
                <p class="text-2xl font-bold text-neutral-900 dark:text-white">0</p>
                <p class="text-xs text-neutral-400 mt-1">Coming in Applications module</p>
            </div>
        </div>
    </div>
</div>
