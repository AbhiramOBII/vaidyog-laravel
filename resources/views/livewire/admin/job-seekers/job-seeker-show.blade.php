<div>
    @php $profile = $seeker->jobSeekerProfile; @endphp

    {{-- FLASH --}}
    @if(session()->has('message'))
        <div class="mb-4 p-3 rounded-lg bg-[#4ab098]/10 border border-[#4ab098]/20 text-[#4ab098] text-sm font-medium">{{ session('message') }}</div>
    @endif

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-6">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-xl bg-[#464d79]/10 flex items-center justify-center text-[#464d79] font-bold text-xl shrink-0">
                @if($profile?->profile_photo_path)
                    <img src="{{ Storage::url($profile->profile_photo_path) }}" class="w-16 h-16 rounded-xl object-cover"/>
                @else
                    {{ strtoupper(substr($seeker->name, 0, 2)) }}
                @endif
            </div>
            <div>
                <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">{{ $profile?->getFullName() ?? $seeker->name }}</h1>
                <div class="flex items-center gap-2 mt-1.5 flex-wrap">
                    @if($profile?->designation)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-[#464d79]/10 text-[#464d79] dark:text-indigo-300">{{ $profile->designation }}</span>
                    @endif
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium {{ $seeker->status === \App\Enums\UserStatusEnum::Active ? 'bg-green-50 text-green-700' : ($seeker->status === \App\Enums\UserStatusEnum::Blocked ? 'bg-red-50 text-red-600' : 'bg-amber-50 text-amber-600') }}">{{ $seeker->status->label() }}</span>
                    @if($seeker->is_profile_completed)
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded text-xs font-medium bg-[#4ab098]/10 text-[#4ab098]">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            Profile Complete
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-amber-50 text-amber-600">Incomplete</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            @if(auth('admin')->user()->hasPermission('job_seekers.edit'))
            <a href="{{ route('admin.job-seekers.edit', $seeker) }}" wire:navigate class="px-4 py-2 text-sm font-medium bg-white dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-700 rounded-lg hover:bg-neutral-50 transition-colors">Edit</a>
            @endif
            @if(auth('admin')->user()->hasPermission('job_seekers.edit'))
                @if($seeker->status === \App\Enums\UserStatusEnum::Active)
                    <button wire:click="changeStatus('blocked')" wire:confirm="Block this user?" class="px-4 py-2 text-sm font-medium text-red-600 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 transition-colors">Block</button>
                @else
                    <button wire:click="changeStatus('active')" wire:confirm="Activate this user?" class="px-4 py-2 text-sm font-medium text-[#4ab098] bg-[#4ab098]/10 border border-[#4ab098]/20 rounded-lg hover:bg-[#4ab098]/20 transition-colors">Activate</button>
                @endif
            @endif
            <a href="{{ route('admin.job-seekers.index') }}" wire:navigate class="px-4 py-2 text-sm font-medium text-neutral-500 hover:text-neutral-700 transition-colors">&larr; Back</a>
        </div>
    </div>

    {{-- SUMMARY CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-5">
            <h3 class="text-xs font-semibold text-neutral-500 uppercase tracking-wider mb-3">Contact Details</h3>
            <dl class="space-y-2 text-sm">
                <div><dt class="text-neutral-500 text-xs">Email</dt><dd class="font-medium text-neutral-800 dark:text-neutral-200">{{ $seeker->email ?? '—' }}</dd></div>
                <div><dt class="text-neutral-500 text-xs">Phone</dt><dd class="font-medium text-neutral-800 dark:text-neutral-200">{{ $seeker->phone ?? '—' }}</dd></div>
                <div><dt class="text-neutral-500 text-xs">Auth Provider</dt><dd class="font-medium text-neutral-800 dark:text-neutral-200">{{ $seeker->auth_provider?->label() ?? '—' }}</dd></div>
                <div><dt class="text-neutral-500 text-xs">Registered</dt><dd class="font-medium text-neutral-800 dark:text-neutral-200">{{ $seeker->created_at->format('d M Y') }}</dd></div>
            </dl>
        </div>
        <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-5">
            <h3 class="text-xs font-semibold text-neutral-500 uppercase tracking-wider mb-3">Classification</h3>
            <dl class="space-y-2 text-sm">
                <div><dt class="text-neutral-500 text-xs">Category</dt><dd class="font-medium text-neutral-800 dark:text-neutral-200">{{ $profile?->category_name ?? '—' }}</dd></div>
                <div><dt class="text-neutral-500 text-xs">Sub-category</dt><dd class="font-medium text-neutral-800 dark:text-neutral-200">{{ $profile?->subcategory_name ?? '—' }}</dd></div>
                <div><dt class="text-neutral-500 text-xs">Specialty</dt><dd class="font-medium text-neutral-800 dark:text-neutral-200">{{ $profile?->specialty?->name ?? '—' }}</dd></div>
                <div><dt class="text-neutral-500 text-xs">Designation</dt><dd class="font-medium text-neutral-800 dark:text-neutral-200">{{ $profile?->designation ?? '—' }}</dd></div>
            </dl>
        </div>
        <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-5">
            <h3 class="text-xs font-semibold text-neutral-500 uppercase tracking-wider mb-3">Professional Info</h3>
            <dl class="space-y-2 text-sm">
                <div><dt class="text-neutral-500 text-xs">Experience</dt><dd class="font-medium text-neutral-800 dark:text-neutral-200">{{ $profile?->experience_years !== null ? $profile->experience_years . ' years' : '—' }}</dd></div>
                <div><dt class="text-neutral-500 text-xs">Qualification</dt><dd class="font-medium text-neutral-800 dark:text-neutral-200">{{ $profile?->highest_qualification ?? '—' }}</dd></div>
                <div><dt class="text-neutral-500 text-xs">Current Employer</dt><dd class="font-medium text-neutral-800 dark:text-neutral-200">{{ $profile?->current_employer ?? '—' }}</dd></div>
                <div><dt class="text-neutral-500 text-xs">Open to Work</dt><dd class="font-medium text-neutral-800 dark:text-neutral-200">{{ $profile?->is_open_to_work ? 'Yes' : 'No' }}</dd></div>
            </dl>
        </div>
        <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-5">
            <h3 class="text-xs font-semibold text-neutral-500 uppercase tracking-wider mb-3">Location</h3>
            <dl class="space-y-2 text-sm">
                <div><dt class="text-neutral-500 text-xs">Country</dt><dd class="font-medium text-neutral-800 dark:text-neutral-200">{{ $profile?->country ?? '—' }}</dd></div>
                <div><dt class="text-neutral-500 text-xs">State</dt><dd class="font-medium text-neutral-800 dark:text-neutral-200">{{ $profile?->state ?? '—' }}</dd></div>
                <div><dt class="text-neutral-500 text-xs">City</dt><dd class="font-medium text-neutral-800 dark:text-neutral-200">{{ $profile?->city ?? '—' }}</dd></div>
                <div><dt class="text-neutral-500 text-xs">Pincode</dt><dd class="font-medium text-neutral-800 dark:text-neutral-200">{{ $profile?->pincode ?? '—' }}</dd></div>
            </dl>
        </div>
    </div>

    {{-- ABOUT & SKILLS --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <div class="lg:col-span-2 bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
            <h3 class="text-sm font-semibold text-neutral-900 dark:text-white mb-3">About</h3>
            <p class="text-sm text-neutral-600 dark:text-neutral-400 leading-relaxed">{{ $profile?->about ?? 'No description provided.' }}</p>

            @if(!empty($profile?->key_skills))
                <div class="mt-4 pt-4 border-t border-neutral-100 dark:border-neutral-700">
                    <span class="text-xs text-neutral-500 block mb-2">Key Skills</span>
                    <div class="flex flex-wrap gap-2">
                        @foreach($profile->key_skills as $skill)
                            <span class="px-2.5 py-1 rounded-md bg-[#464d79]/10 text-[#464d79] dark:text-indigo-300 text-xs font-medium">{{ $skill }}</span>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- Resume & Documents --}}
        <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
            <h3 class="text-sm font-semibold text-neutral-900 dark:text-white mb-3">Resume & Documents</h3>
            @if($profile?->resume_path)
                <a href="{{ Storage::url($profile->resume_path) }}" target="_blank" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-[#464d79]/10 text-[#464d79] text-sm font-medium hover:bg-[#464d79]/20 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Download Resume
                </a>
            @else
                <p class="text-sm text-neutral-500">No resume uploaded.</p>
            @endif

            @if($profile?->certifications && $profile->certifications->count() > 0)
                <div class="mt-4 pt-4 border-t border-neutral-100 dark:border-neutral-700">
                    <span class="text-xs text-neutral-500 block mb-2">Certifications ({{ $profile->certifications->count() }})</span>
                    <div class="space-y-2">
                        @foreach($profile->certifications as $cert)
                            <div class="text-xs p-2 rounded bg-neutral-50 dark:bg-neutral-900">
                                <span class="text-neutral-700 dark:text-neutral-300 font-medium">{{ $cert->name }}</span>
                                @if($cert->issuing_organization)
                                    <span class="text-neutral-500"> &middot; {{ $cert->issuing_organization }}</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- EDUCATION & EXPERIENCE --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        {{-- Education --}}
        <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
            <h3 class="text-sm font-semibold text-neutral-900 dark:text-white mb-3">Education</h3>
            @if($profile?->educations && $profile->educations->count() > 0)
                <div class="space-y-3">
                    @foreach($profile->educations as $edu)
                        <div class="p-3 rounded-lg bg-neutral-50 dark:bg-neutral-900">
                            <p class="text-sm font-medium text-neutral-800 dark:text-neutral-200">{{ $edu->degree ?? $edu->qualification ?? '—' }}</p>
                            @if($edu->institution)<p class="text-xs text-neutral-500">{{ $edu->institution }}</p>@endif
                            @if($edu->year_of_passing)<p class="text-xs text-neutral-400">{{ $edu->year_of_passing }}</p>@endif
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-neutral-500">No education records.</p>
            @endif
        </div>

        {{-- Work Experience --}}
        <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
            <h3 class="text-sm font-semibold text-neutral-900 dark:text-white mb-3">Work Experience</h3>
            @if($profile?->employments && $profile->employments->count() > 0)
                <div class="space-y-3">
                    @foreach($profile->employments as $emp)
                        <div class="p-3 rounded-lg bg-neutral-50 dark:bg-neutral-900">
                            <p class="text-sm font-medium text-neutral-800 dark:text-neutral-200">{{ $emp->job_title ?? '—' }}</p>
                            @if($emp->company_name)<p class="text-xs text-neutral-500">{{ $emp->company_name }}</p>@endif
                            <p class="text-xs text-neutral-400">
                                {{ $emp->start_date ? \Carbon\Carbon::parse($emp->start_date)->format('M Y') : '' }}
                                {{ $emp->end_date ? '— ' . \Carbon\Carbon::parse($emp->end_date)->format('M Y') : ($emp->is_current ? '— Present' : '') }}
                            </p>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-neutral-500">No work experience records.</p>
            @endif
        </div>
    </div>

    {{-- ADMIN META --}}
    <div class="bg-neutral-50 dark:bg-neutral-900 rounded-xl border border-neutral-200 dark:border-neutral-700 p-4">
        <div class="flex items-center gap-6 text-xs text-neutral-500">
            @if($profile?->createdByAdmin)
                <span>Created by admin: <strong class="text-neutral-700 dark:text-neutral-300">{{ $profile->createdByAdmin->name }}</strong></span>
            @endif
            <span>Registered: {{ $seeker->created_at->format('d M Y H:i') }}</span>
            <span>Updated: {{ $seeker->updated_at->format('d M Y H:i') }}</span>
        </div>
    </div>
</div>
