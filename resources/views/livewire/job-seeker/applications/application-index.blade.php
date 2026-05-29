<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">My Applications</h1>
            <p class="text-sm text-neutral-500 mt-1">Track and manage all your job applications in one place.</p>
        </div>
        <a href="{{ route('jobs.index') }}" wire:navigate class="inline-flex items-center gap-2 px-4 h-10 text-sm font-semibold text-white rounded-xl transition-all hover:opacity-90 shrink-0" style="background: linear-gradient(146deg, rgba(70,77,121,1) 26%, rgba(74,176,152,1) 100%);">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            Browse Jobs
        </a>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-neutral-800 rounded-2xl border border-neutral-200 dark:border-neutral-700 p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-[#464d79]/10 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-[#464d79]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <div>
                <p class="text-xl font-bold text-neutral-900 dark:text-white">{{ $stats['total'] }}</p>
                <p class="text-[11px] text-neutral-500 font-medium">Total Applied</p>
            </div>
        </div>
        <div class="bg-white dark:bg-neutral-800 rounded-2xl border border-neutral-200 dark:border-neutral-700 p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            </div>
            <div>
                <p class="text-xl font-bold text-indigo-600">{{ $stats['underReview'] }}</p>
                <p class="text-[11px] text-neutral-500 font-medium">Under Review</p>
            </div>
        </div>
        <div class="bg-white dark:bg-neutral-800 rounded-2xl border border-neutral-200 dark:border-neutral-700 p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <div>
                <p class="text-xl font-bold text-amber-600">{{ $stats['interviews'] }}</p>
                <p class="text-[11px] text-neutral-500 font-medium">Interviews</p>
            </div>
        </div>
        <div class="bg-white dark:bg-neutral-800 rounded-2xl border border-neutral-200 dark:border-neutral-700 p-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-green-100 dark:bg-green-900/30 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-xl font-bold text-green-600">{{ $stats['offers'] }}</p>
                <p class="text-[11px] text-neutral-500 font-medium">Offers</p>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white dark:bg-neutral-800 rounded-2xl border border-neutral-200 dark:border-neutral-700 p-4">
        <div class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search job title or institution..." class="w-full h-10 pl-10 pr-4 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 rounded-xl text-sm focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20 transition-colors"/>
            </div>
            <select wire:model.live="status" class="h-10 px-4 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 rounded-xl text-sm focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20 transition-colors sm:w-48">
                <option value="">All Statuses</option>
                @foreach($statuses as $s)
                    <option value="{{ $s->value }}">{{ $s->label() }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Application Cards --}}
    <div class="space-y-4">
        @forelse($applications as $app)
        @php
            $steps = ['applied', 'reviewed', 'shortlisted', 'interviewed', 'offered'];
            $currentIndex = array_search($app->status->value, $steps);
            $isRejected = $app->status->value === 'rejected';
            $isOffered = $app->status->value === 'offered';
        @endphp
        <a href="{{ route('jobseeker.applications.show', $app) }}" wire:navigate class="block group">
            <div class="bg-white dark:bg-neutral-800 rounded-2xl border border-neutral-200 dark:border-neutral-700 p-5 hover:border-[#464d79]/30 hover:shadow-md transition-all duration-200">
                <div class="flex items-start gap-4">
                    {{-- Thumbnail --}}
                    @if($app->job)
                    <img src="{{ $app->job->getThumbnailUrl() }}" alt="{{ $app->job->job_title }}" class="w-12 h-12 rounded-xl object-cover shrink-0 border border-neutral-100 dark:border-neutral-700"/>
                    @else
                    <div class="w-12 h-12 rounded-xl bg-neutral-100 dark:bg-neutral-700 shrink-0 flex items-center justify-center">
                        <svg class="w-5 h-5 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    @endif

                    {{-- Content --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <h3 class="font-semibold text-neutral-900 dark:text-white text-sm truncate group-hover:text-[#464d79] transition-colors">{{ $app->job?->job_title ?? 'Job Deleted' }}</h3>
                                <p class="text-xs text-neutral-500 mt-0.5">{{ $app->job?->institution_name }}</p>
                            </div>
                            <span class="inline-flex px-2.5 py-1 rounded-full text-[11px] font-semibold shrink-0 {{ $app->status->getBadgeClasses() }}">{{ $app->status->label() }}</span>
                        </div>

                        {{-- Meta info --}}
                        <div class="flex flex-wrap items-center gap-x-4 gap-y-1 mt-2.5">
                            @if($app->job?->location_city)
                            <span class="inline-flex items-center gap-1 text-xs text-neutral-400">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                {{ $app->job->location_city }}
                            </span>
                            @endif
                            @if($app->job?->employment_type)
                            <span class="inline-flex items-center gap-1 text-xs text-neutral-400">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ $app->job->employment_type->label() }}
                            </span>
                            @endif
                            <span class="inline-flex items-center gap-1 text-xs text-neutral-400">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                Applied {{ $app->applied_at->diffForHumans() }}
                            </span>
                        </div>

                        {{-- Progress bar --}}
                        <div class="mt-4 flex items-center gap-1">
                            @foreach($steps as $i => $step)
                            <div class="flex-1 h-1.5 rounded-full transition-colors {{ $isRejected ? 'bg-red-200 dark:bg-red-900/30' : ($i <= $currentIndex ? 'bg-[#4ab098]' : 'bg-neutral-100 dark:bg-neutral-700') }}"></div>
                            @endforeach
                        </div>
                        <div class="flex items-center justify-between mt-1.5">
                            <span class="text-[10px] text-neutral-400">Applied</span>
                            <span class="text-[10px] text-neutral-400">{{ $isRejected ? 'Not Selected' : ($isOffered ? 'Offered!' : 'Offered') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </a>
        @empty
        <div class="bg-white dark:bg-neutral-800 rounded-2xl border border-neutral-200 dark:border-neutral-700 p-12 text-center">
            <div class="w-16 h-16 mx-auto mb-4 bg-neutral-100 dark:bg-neutral-700 rounded-full flex items-center justify-center">
                <svg class="w-7 h-7 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <h3 class="text-lg font-semibold text-neutral-700 dark:text-neutral-300">No Applications Yet</h3>
            <p class="text-sm text-neutral-500 mt-1 max-w-sm mx-auto">Start your healthcare career journey by browsing available positions and applying to jobs that match your skills.</p>
            <a href="{{ route('jobs.index') }}" wire:navigate class="mt-5 inline-flex items-center gap-2 px-5 h-10 text-sm font-semibold text-white rounded-xl" style="background: linear-gradient(146deg, rgba(70,77,121,1) 26%, rgba(74,176,152,1) 100%);">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                Browse Jobs
            </a>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($applications->hasPages())
    <div class="pt-2">{{ $applications->links() }}</div>
    @endif
</div>
