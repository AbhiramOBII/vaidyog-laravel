@php
    $statusOrder = ['applied', 'reviewed', 'shortlisted', 'interviewed', 'offered'];
    $currentIdx = array_search($application->status->value, $statusOrder);
    $isRejected = $application->status->value === 'rejected';
    $isOffered = $application->status->value === 'offered';
@endphp

<div class="space-y-6">
    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm">
        <a href="{{ route('jobseeker.applications.index') }}" wire:navigate class="text-neutral-500 hover:text-[#464d79] transition-colors">My Applications</a>
        <svg class="w-3.5 h-3.5 text-neutral-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-neutral-900 dark:text-white font-medium truncate max-w-[200px]">{{ $application->job?->job_title ?? 'Application' }}</span>
    </nav>

    {{-- Status Hero Card --}}
    @if($isOffered)
    <div class="relative overflow-hidden rounded-2xl p-6 md:p-8" style="background: linear-gradient(146deg, rgba(22,163,74,1) 0%, rgba(74,176,152,1) 100%);">
        <div class="absolute top-0 right-0 w-48 h-48 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/3"></div>
        <div class="relative z-10 text-center">
            <div class="w-16 h-16 mx-auto mb-4 bg-white/20 rounded-full flex items-center justify-center">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h2 class="text-xl md:text-2xl font-bold text-white">Congratulations! Offer Extended</h2>
            <p class="text-white/80 text-sm mt-2 max-w-md mx-auto">The recruiter has extended an offer for this position. Please respond at your earliest convenience.</p>
        </div>
    </div>
    @elseif($isRejected)
    <div class="relative overflow-hidden rounded-2xl bg-neutral-100 dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-700 p-6 md:p-8">
        <div class="text-center">
            <div class="w-14 h-14 mx-auto mb-3 bg-neutral-200 dark:bg-neutral-700 rounded-full flex items-center justify-center">
                <svg class="w-7 h-7 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </div>
            <h2 class="text-lg font-semibold text-neutral-700 dark:text-neutral-300">Application Not Selected</h2>
            <p class="text-sm text-neutral-500 mt-1 max-w-sm mx-auto">This application was not progressed further. Don't lose hope — keep applying!</p>
            <a href="{{ route('jobs.index') }}" wire:navigate class="inline-flex items-center gap-1.5 mt-4 text-sm font-medium text-[#464d79] hover:underline">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                Browse More Jobs
            </a>
        </div>
    </div>
    @else
    {{-- Progress Stepper --}}
    <div class="bg-white dark:bg-neutral-800 rounded-2xl border border-neutral-200 dark:border-neutral-700 p-6">
        <div class="flex items-center justify-between mb-2">
            <h3 class="text-sm font-semibold text-neutral-500 uppercase tracking-wide">Application Progress</h3>
            <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold {{ $application->status->getBadgeClasses() }}">{{ $application->status->label() }}</span>
        </div>
        <div class="mt-6">
            <div class="flex items-center justify-between relative">
                {{-- Progress bar background --}}
                <div class="absolute top-3 left-0 right-0 h-0.5 bg-neutral-200 dark:bg-neutral-700"></div>
                {{-- Filled progress --}}
                @if($currentIdx !== false)
                <div class="absolute top-3 left-0 h-0.5 bg-[#4ab098] transition-all duration-500" style="width: {{ ($currentIdx / (count($statusOrder) - 1)) * 100 }}%"></div>
                @endif

                @foreach($statusOrder as $idx => $step)
                @php
                    $isCompleted = $currentIdx !== false && $idx < $currentIdx;
                    $isCurrent = $currentIdx !== false && $idx === $currentIdx;
                @endphp
                <div class="relative flex flex-col items-center z-10" style="width: {{ 100 / count($statusOrder) }}%">
                    <div class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold transition-all duration-300
                        {{ $isCompleted ? 'bg-[#4ab098] text-white' : ($isCurrent ? 'bg-[#464d79] text-white ring-4 ring-[#464d79]/20' : 'bg-neutral-200 dark:bg-neutral-700 text-neutral-400') }}">
                        @if($isCompleted)
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        @else
                        {{ $idx + 1 }}
                        @endif
                    </div>
                    <span class="mt-2 text-[10px] md:text-xs font-medium capitalize {{ $isCurrent ? 'text-[#464d79] dark:text-white' : ($isCompleted ? 'text-neutral-700 dark:text-neutral-300' : 'text-neutral-400') }}">{{ str_replace('_', ' ', $step) }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Column --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Job Card --}}
            <div class="bg-white dark:bg-neutral-800 rounded-2xl border border-neutral-200 dark:border-neutral-700 p-6">
                <div class="flex items-start gap-4">
                    @if($application->job)
                    <img src="{{ $application->job->getThumbnailUrl() }}" alt="{{ $application->job->job_title }}" class="w-14 h-14 rounded-xl object-cover shrink-0 border border-neutral-100"/>
                    @endif
                    <div class="flex-1 min-w-0">
                        <h2 class="text-lg font-bold text-neutral-900 dark:text-white">{{ $application->job?->job_title }}</h2>
                        <p class="text-sm text-neutral-500 mt-0.5">{{ $application->job?->institution_name }}</p>
                        <div class="flex flex-wrap items-center gap-2 mt-3">
                            @if($application->job?->location_city)
                            <span class="inline-flex items-center gap-1 text-xs text-neutral-500 bg-neutral-100 dark:bg-neutral-700 px-2.5 py-1 rounded-full">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                {{ collect([$application->job->location_city, $application->job->location_state])->filter()->join(', ') }}
                            </span>
                            @endif
                            @if($application->job?->employment_type)
                            <span class="text-xs text-[#464d79] bg-[#464d79]/10 px-2.5 py-1 rounded-full font-medium">{{ $application->job->employment_type->label() }}</span>
                            @endif
                            @if($application->job?->is_remote)
                            <span class="text-xs text-[#4ab098] bg-[#4ab098]/10 px-2.5 py-1 rounded-full font-medium">Remote</span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Job details grid --}}
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mt-5 pt-5 border-t border-neutral-100 dark:border-neutral-700">
                    <div>
                        <p class="text-[11px] font-medium text-neutral-400 uppercase tracking-wide">Salary</p>
                        <p class="text-sm font-semibold text-neutral-900 dark:text-white mt-0.5">
                            @if($application->job?->salary_disclosed && $application->job?->salary_min)
                            &#8377;{{ number_format($application->job->salary_min) }} - &#8377;{{ number_format($application->job->salary_max) }}
                            @else
                            As per industry norms
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-[11px] font-medium text-neutral-400 uppercase tracking-wide">Experience</p>
                        <p class="text-sm font-semibold text-neutral-900 dark:text-white mt-0.5">
                            @if($application->job?->experience_min !== null)
                            {{ $application->job->experience_min }} - {{ $application->job->experience_max }} yrs
                            @else
                            Not specified
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-[11px] font-medium text-neutral-400 uppercase tracking-wide">Vacancies</p>
                        <p class="text-sm font-semibold text-neutral-900 dark:text-white mt-0.5">{{ $application->job?->number_of_vacancies ?? '—' }}</p>
                    </div>
                </div>

                @if($application->job)
                <a href="{{ route('jobs.show', $application->job) }}" wire:navigate class="inline-flex items-center gap-1.5 mt-5 text-sm font-medium text-[#464d79] hover:text-[#464d79]/80 transition-colors">
                    View Full Job Details
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
                @endif
            </div>

            {{-- Your Submission --}}
            <div class="bg-white dark:bg-neutral-800 rounded-2xl border border-neutral-200 dark:border-neutral-700 p-6">
                <h3 class="text-sm font-semibold text-neutral-900 dark:text-white mb-4 flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#464d79]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Your Submission
                </h3>

                @if($application->cover_note)
                <div class="mb-4">
                    <p class="text-xs font-medium text-neutral-400 uppercase tracking-wide mb-1.5">Cover Note</p>
                    <div class="bg-neutral-50 dark:bg-neutral-900 rounded-xl p-4 border border-neutral-100 dark:border-neutral-700">
                        <p class="text-sm text-neutral-700 dark:text-neutral-300 leading-relaxed">{{ $application->cover_note }}</p>
                    </div>
                </div>
                @endif

                <div class="flex items-center justify-between">
                    @if($application->resume_path)
                    <a href="{{ asset('storage/' . $application->resume_path) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2.5 bg-neutral-100 dark:bg-neutral-700 hover:bg-neutral-200 dark:hover:bg-neutral-600 rounded-xl transition-colors">
                        <svg class="w-5 h-5 text-[#464d79]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span class="text-sm font-medium text-neutral-700 dark:text-neutral-300">Download Resume</span>
                    </a>
                    @else
                    <div class="flex items-center gap-2 text-neutral-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span class="text-sm">No resume submitted</span>
                    </div>
                    @endif
                    <p class="text-xs text-neutral-400">Applied {{ $application->applied_at->diffForHumans() }}</p>
                </div>
            </div>

            {{-- Withdraw Section --}}
            @if(in_array($application->status->value, ['applied', 'reviewed']))
            <div class="bg-red-50 dark:bg-red-950/20 rounded-2xl border border-red-100 dark:border-red-900/30 p-5">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-sm font-semibold text-red-700 dark:text-red-400">Withdraw Application</h4>
                        <p class="text-xs text-red-600/70 dark:text-red-400/70 mt-0.5">This action cannot be undone. The recruiter will no longer see your application.</p>
                        <button wire:click="withdraw" wire:confirm="Are you sure you want to withdraw? This cannot be undone." class="mt-3 px-4 h-8 bg-red-600 text-white text-xs font-semibold rounded-lg hover:bg-red-700 transition-colors">
                            Withdraw Application
                        </button>
                    </div>
                </div>
            </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            {{-- Timeline --}}
            <div class="bg-white dark:bg-neutral-800 rounded-2xl border border-neutral-200 dark:border-neutral-700 p-5">
                <h3 class="text-sm font-semibold text-neutral-900 dark:text-white mb-4 flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#4ab098]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Activity Timeline
                </h3>
                <div class="relative">
                    <div class="absolute left-[7px] top-2 bottom-2 w-0.5 bg-neutral-100 dark:bg-neutral-700"></div>
                    <div class="space-y-5">
                        @foreach($application->getStatusHistory() as $status => $timestamp)
                        @php
                            $isLatest = $loop->last;
                            $stepColor = match($status) {
                                'offered' => 'bg-green-500',
                                'rejected' => 'bg-red-400',
                                default => ($isLatest ? 'bg-[#464d79]' : 'bg-[#4ab098]'),
                            };
                        @endphp
                        <div class="flex items-start gap-3 relative">
                            <div class="w-[15px] h-[15px] rounded-full {{ $stepColor }} shrink-0 z-10 {{ $isLatest ? 'ring-4 ring-[#464d79]/10' : '' }}"></div>
                            <div class="flex-1 -mt-0.5">
                                <p class="text-sm font-medium text-neutral-900 dark:text-white capitalize">{{ str_replace('_', ' ', $status) }}</p>
                                <p class="text-[11px] text-neutral-400 mt-0.5">{{ \Carbon\Carbon::parse($timestamp)->format('M d, Y \a\t h:i A') }}</p>
                                <p class="text-[11px] text-neutral-400">{{ \Carbon\Carbon::parse($timestamp)->diffForHumans() }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Quick Info --}}
            <div class="bg-white dark:bg-neutral-800 rounded-2xl border border-neutral-200 dark:border-neutral-700 p-5">
                <h3 class="text-sm font-semibold text-neutral-900 dark:text-white mb-4">Quick Info</h3>
                <dl class="space-y-3">
                    <div class="flex items-center justify-between">
                        <dt class="text-xs text-neutral-500">Status</dt>
                        <dd><span class="inline-flex px-2 py-0.5 rounded-full text-[11px] font-semibold {{ $application->status->getBadgeClasses() }}">{{ $application->status->label() }}</span></dd>
                    </div>
                    <div class="flex items-center justify-between border-t border-neutral-100 dark:border-neutral-700 pt-3">
                        <dt class="text-xs text-neutral-500">Applied</dt>
                        <dd class="text-xs font-medium text-neutral-700 dark:text-neutral-300">{{ $application->applied_at->format('M d, Y') }}</dd>
                    </div>
                    @if($application->job?->expires_at)
                    <div class="flex items-center justify-between border-t border-neutral-100 dark:border-neutral-700 pt-3">
                        <dt class="text-xs text-neutral-500">Job Expires</dt>
                        <dd class="text-xs font-medium {{ $application->job->expires_at->isPast() ? 'text-red-500' : 'text-neutral-700 dark:text-neutral-300' }}">{{ $application->job->expires_at->format('M d, Y') }}</dd>
                    </div>
                    @endif
                    @if($application->matching_skills && count($application->matching_skills) > 0)
                    <div class="border-t border-neutral-100 dark:border-neutral-700 pt-3">
                        <dt class="text-xs text-neutral-500 mb-2">Matching Skills</dt>
                        <dd class="flex flex-wrap gap-1.5">
                            @foreach($application->matching_skills as $skill)
                            <span class="text-[11px] px-2 py-0.5 rounded-full bg-[#4ab098]/10 text-[#4ab098] font-medium">{{ $skill }}</span>
                            @endforeach
                        </dd>
                    </div>
                    @endif
                </dl>
            </div>

            {{-- Help Card --}}
            <div class="bg-gradient-to-br from-[#464d79]/5 to-[#4ab098]/5 rounded-2xl border border-neutral-200 dark:border-neutral-700 p-5">
                <div class="flex items-center gap-2 mb-2">
                    <svg class="w-4 h-4 text-[#464d79]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <h4 class="text-sm font-semibold text-neutral-700 dark:text-neutral-300">Need Help?</h4>
                </div>
                <p class="text-xs text-neutral-500 leading-relaxed">If you have questions about your application status or need assistance, reach out to our support team.</p>
                <a href="mailto:support@vaidyog.com" class="inline-flex items-center gap-1 mt-3 text-xs font-medium text-[#464d79] hover:underline">
                    Contact Support
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>
        </div>
    </div>
</div>
