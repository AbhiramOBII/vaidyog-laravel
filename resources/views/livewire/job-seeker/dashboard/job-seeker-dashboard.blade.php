<div class="space-y-10">
    {{-- Welcome Banner --}}
    <div class="rounded-2xl p-6 sm:p-8 text-white relative overflow-hidden" style="background: linear-gradient(146deg, rgba(70, 77, 121, 1) 26%, rgba(74, 176, 152, 1) 100%);">
        <div class="absolute top-0 right-0 w-72 h-72 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/4"></div>
        <div class="absolute bottom-0 left-0 w-56 h-56 bg-white/5 rounded-full translate-y-1/2 -translate-x-1/4"></div>
        <div class="absolute top-1/2 right-1/4 w-32 h-32 bg-white/5 rounded-full"></div>
        <div class="relative z-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-1">
                    Welcome back, {{ $profile?->first_name ?? auth()->user()->name }}!
                </h1>
                <p class="text-white/80 text-sm sm:text-base">Find your perfect healthcare career opportunity.</p>
            </div>
            <a href="{{ route('profile.edit') }}" wire:navigate class="inline-flex items-center gap-2 px-5 py-2.5 bg-white/20 hover:bg-white/30 backdrop-blur rounded-lg text-sm font-medium text-white transition-colors shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit Profile
            </a>
        </div>
    </div>

    {{-- Stats Cards — Colourful --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        {{-- Profile Completeness --}}
        <div class="rounded-xl p-5 text-white relative overflow-hidden" style="background: linear-gradient(135deg, #464d79 0%, #5b63a0 100%);">
            <div class="absolute -top-4 -right-4 w-20 h-20 bg-white/10 rounded-full"></div>
            <div class="relative">
                <svg class="w-8 h-8 text-white/80 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                <p class="text-2xl font-bold">{{ $profile?->profile_completeness ?? 0 }}%</p>
                <p class="text-xs text-white/70 mt-0.5">Profile Complete</p>
            </div>
        </div>

        {{-- Applications --}}
        <div class="rounded-xl p-5 text-white relative overflow-hidden" style="background: linear-gradient(135deg, #4ab098 0%, #3d9680 100%);">
            <div class="absolute -top-4 -right-4 w-20 h-20 bg-white/10 rounded-full"></div>
            <div class="relative">
                <svg class="w-8 h-8 text-white/80 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <p class="text-2xl font-bold">{{ $applicationCount }}</p>
                <p class="text-xs text-white/70 mt-0.5">Applications</p>
            </div>
        </div>

        {{-- Saved Jobs --}}
        <div class="rounded-xl p-5 text-white relative overflow-hidden" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
            <div class="absolute -top-4 -right-4 w-20 h-20 bg-white/10 rounded-full"></div>
            <div class="relative">
                <svg class="w-8 h-8 text-white/80 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
                <p class="text-2xl font-bold">{{ $savedJobCount }}</p>
                <p class="text-xs text-white/70 mt-0.5">Saved Jobs</p>
            </div>
        </div>

        {{-- Open to Work --}}
        <div class="rounded-xl p-5 text-white relative overflow-hidden" style="background: linear-gradient(135deg, {{ $profile?->is_open_to_work ? '#10b981' : '#6b7280' }} 0%, {{ $profile?->is_open_to_work ? '#059669' : '#4b5563' }} 100%);">
            <div class="absolute -top-4 -right-4 w-20 h-20 bg-white/10 rounded-full"></div>
            <div class="relative">
                <svg class="w-8 h-8 text-white/80 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                <p class="text-lg font-bold">{{ $profile?->is_open_to_work ? 'Active' : 'Inactive' }}</p>
                <p class="text-xs text-white/70 mt-0.5">Open to Work</p>
            </div>
        </div>
    </div>

    {{-- CV Upload Reminder --}}
    @if ($profile && !$profile->resume_path)
    <div class="flex items-start gap-3 p-4 rounded-xl bg-amber-50 dark:bg-amber-900/10 border border-amber-200 dark:border-amber-800">
        <svg class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
        <div class="flex-1">
            <p class="text-sm font-semibold text-amber-800 dark:text-amber-300">Resume/CV not uploaded</p>
            <p class="text-xs text-amber-700 dark:text-amber-400 mt-0.5">Uploading your CV increases your chances of getting shortlisted by recruiters. Add it now to stand out.</p>
        </div>
        <a href="{{ route('profile.edit') }}" wire:navigate class="shrink-0 px-3 py-1.5 text-xs font-medium text-amber-800 bg-amber-100 hover:bg-amber-200 dark:bg-amber-800/30 dark:text-amber-300 dark:hover:bg-amber-800/50 rounded-lg transition-colors">Upload CV</a>
    </div>
    @endif

    {{-- Recent Jobs --}}
    <div>
        <div class="flex items-center justify-between mb-5">
            <div class="flex items-center gap-3">
                <div class="w-1 h-6 rounded-full bg-[#464d79]"></div>
                <h2 class="text-xl font-bold text-neutral-900">Recent Jobs</h2>
            </div>
            <a href="{{ route('jobs.index') }}" wire:navigate class="inline-flex items-center gap-1 text-sm text-[#464d79] font-medium hover:underline">
                View All
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        @if ($recentJobs->isEmpty())
            <div class="bg-white rounded-xl border border-neutral-200 p-8 text-center">
                <svg class="w-12 h-12 mx-auto text-neutral-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                <p class="text-neutral-500 text-sm">No jobs available at the moment.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($recentJobs as $job)
                    @include('partials.job-card', ['job' => $job, 'featured' => false])
                @endforeach
            </div>
        @endif
    </div>

    {{-- Latest News — Blog style, full-width row --}}
    <div>
        <div class="flex items-center justify-between mb-5">
            <div class="flex items-center gap-3">
                <div class="w-1 h-6 rounded-full bg-[#4ab098]"></div>
                <h2 class="text-xl font-bold text-neutral-900">Latest News</h2>
            </div>
        </div>

        @if ($latestNews->isEmpty())
            <div class="bg-white rounded-xl border border-neutral-200 p-8 text-center">
                <svg class="w-12 h-12 mx-auto text-neutral-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                <p class="text-sm text-neutral-500">No news articles yet.</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                @foreach ($latestNews as $news)
                    <div class="bg-white rounded-xl border border-neutral-200 overflow-hidden hover:shadow-lg hover:-translate-y-0.5 transition-all group">
                        {{-- Thumbnail --}}
                        <div class="aspect-[16/10] bg-gradient-to-br from-[#464d79]/20 to-[#4ab098]/20 relative overflow-hidden">
                            @if ($news->thumbnail_image)
                                <img src="{{ asset('storage/' . $news->thumbnail_image) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" alt="">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <svg class="w-10 h-10 text-[#464d79]/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                                </div>
                            @endif
                            @if ($news->category)
                                <span class="absolute top-3 left-3 text-xs px-2.5 py-1 rounded-full bg-white/90 text-[#464d79] font-semibold shadow-sm">{{ $news->category->name }}</span>
                            @endif
                        </div>
                        {{-- Content --}}
                        <div class="p-4">
                            <h3 class="text-sm font-semibold text-neutral-900 line-clamp-2 group-hover:text-[#464d79] transition-colors">{{ $news->title }}</h3>
                            @if ($news->short_description)
                                <p class="text-xs text-neutral-500 mt-2 line-clamp-2">{{ $news->short_description }}</p>
                            @endif
                            <p class="text-xs text-neutral-400 mt-3">{{ $news->published_at?->format('M d, Y') ?? $news->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Upcoming Events — Blog style, full-width row --}}
    <div>
        <div class="flex items-center justify-between mb-5">
            <div class="flex items-center gap-3">
                <div class="w-1 h-6 rounded-full bg-amber-500"></div>
                <h2 class="text-xl font-bold text-neutral-900">Upcoming Events</h2>
            </div>
        </div>

        @if ($upcomingEvents->isEmpty())
            <div class="bg-white rounded-xl border border-neutral-200 p-8 text-center">
                <svg class="w-12 h-12 mx-auto text-neutral-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <p class="text-sm text-neutral-500">No upcoming events.</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                @foreach ($upcomingEvents as $event)
                    <div class="bg-white rounded-xl border border-neutral-200 overflow-hidden hover:shadow-lg hover:-translate-y-0.5 transition-all group">
                        {{-- Date Header --}}
                        <div class="p-4 text-center relative overflow-hidden" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                            <div class="absolute -top-6 -right-6 w-16 h-16 bg-white/10 rounded-full"></div>
                            <div class="relative">
                                <p class="text-3xl font-bold text-white leading-none">{{ $event->event_date?->format('d') }}</p>
                                <p class="text-sm font-medium text-white/80 uppercase mt-1">{{ $event->event_date?->format('M Y') }}</p>
                            </div>
                        </div>
                        {{-- Content --}}
                        <div class="p-4">
                            <h3 class="text-sm font-semibold text-neutral-900 line-clamp-2 group-hover:text-amber-700 transition-colors">{{ $event->title }}</h3>
                            <div class="flex items-center gap-2 mt-2">
                                @if ($event->event_type)
                                    <span class="text-xs px-2 py-0.5 rounded-full {{ $event->event_type === 'online' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700' }} font-medium capitalize">{{ $event->event_type }}</span>
                                @endif
                            </div>
                            @if ($event->venue)
                                <p class="text-xs text-neutral-500 mt-2 flex items-center gap-1">
                                    <svg class="w-3 h-3 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                                    <span class="truncate">{{ $event->venue }}</span>
                                </p>
                            @elseif ($event->online_link)
                                <p class="text-xs text-blue-600 mt-2 flex items-center gap-1">
                                    <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                    Online Event
                                </p>
                            @endif
                            @if ($event->short_description)
                                <p class="text-xs text-neutral-500 mt-2 line-clamp-2">{{ $event->short_description }}</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
