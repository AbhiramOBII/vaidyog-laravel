<div>
    {{-- Welcome Banner --}}
    <div class="relative overflow-hidden rounded-2xl p-6 md:p-8 mb-8" style="background: linear-gradient(146deg, rgba(70,77,121,1) 26%, rgba(74,176,152,1) 100%);">
        <div class="absolute top-0 right-0 w-72 h-72 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/3"></div>
        <div class="absolute bottom-0 left-1/2 w-48 h-48 bg-white/5 rounded-full translate-y-1/2"></div>
        <div class="relative z-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-xl md:text-2xl font-bold text-white">Welcome back, {{ auth()->user()->name ?? 'Recruiter' }}</h1>
                <p class="text-white/70 text-sm mt-1">Here's your recruitment activity at a glance.</p>
            </div>
            <a href="{{ route('recruiter.jobs.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white text-[#464d79] text-sm font-bold rounded-xl hover:bg-white/90 transition-colors shrink-0 shadow-lg shadow-black/10">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                Post New Job
            </a>
        </div>
    </div>

    {{-- Metric Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <a href="{{ route('recruiter.jobs.index') }}" class="group bg-white dark:bg-neutral-900 rounded-2xl border border-neutral-200 dark:border-neutral-800 p-5 hover:shadow-lg hover:border-green-200 transition-all">
            <div class="flex items-center justify-between mb-3">
                <span class="w-11 h-11 rounded-xl bg-green-50 dark:bg-green-950/40 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </span>
                <svg class="w-4 h-4 text-neutral-300 dark:text-neutral-600 group-hover:text-green-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </div>
            <div class="text-2xl font-bold text-neutral-900 dark:text-white">{{ $liveJobs }}</div>
            <div class="text-xs text-neutral-500 dark:text-neutral-400 mt-1">Live Jobs</div>
        </a>

        <a href="{{ route('recruiter.jobs.index') }}" class="group bg-white dark:bg-neutral-900 rounded-2xl border border-neutral-200 dark:border-neutral-800 p-5 hover:shadow-lg hover:border-amber-200 transition-all">
            <div class="flex items-center justify-between mb-3">
                <span class="w-11 h-11 rounded-xl bg-amber-50 dark:bg-amber-950/40 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </span>
                <svg class="w-4 h-4 text-neutral-300 dark:text-neutral-600 group-hover:text-amber-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </div>
            <div class="text-2xl font-bold text-neutral-900 dark:text-white">{{ $pendingJobs }}</div>
            <div class="text-xs text-neutral-500 dark:text-neutral-400 mt-1">Pending Approval</div>
        </a>

        <div class="bg-white dark:bg-neutral-900 rounded-2xl border border-neutral-200 dark:border-neutral-800 p-5">
            <div class="flex items-center justify-between mb-3">
                <span class="w-11 h-11 rounded-xl bg-neutral-100 dark:bg-neutral-800 flex items-center justify-center">
                    <svg class="w-5 h-5 text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </span>
            </div>
            <div class="text-2xl font-bold text-neutral-900 dark:text-white">{{ $expiredJobs }}</div>
            <div class="text-xs text-neutral-500 dark:text-neutral-400 mt-1">Completed / Expired</div>
        </div>

        <a href="{{ route('recruiter.applications.index') }}" class="group bg-white dark:bg-neutral-900 rounded-2xl border border-neutral-200 dark:border-neutral-800 p-5 hover:shadow-lg hover:border-blue-200 transition-all">
            <div class="flex items-center justify-between mb-3">
                <span class="w-11 h-11 rounded-xl bg-blue-50 dark:bg-blue-950/40 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </span>
                <svg class="w-4 h-4 text-neutral-300 dark:text-neutral-600 group-hover:text-blue-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </div>
            <div class="text-2xl font-bold text-neutral-900 dark:text-white">{{ $totalApplicants }}</div>
            <div class="text-xs text-neutral-500 dark:text-neutral-400 mt-1">Total Applicants</div>
        </a>
    </div>

    {{-- Charts + Quick Actions --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        {{-- Job Posting Trend --}}
        <div class="lg:col-span-2 bg-white dark:bg-neutral-900 rounded-2xl border border-neutral-200 dark:border-neutral-800 p-6">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-sm font-bold text-neutral-900 dark:text-white">Job Postings — Last 6 Months</h3>
                <span class="text-[11px] text-neutral-400 bg-neutral-100 dark:bg-neutral-800 px-2.5 py-1 rounded-full">{{ array_sum($chartData) }} total</span>
            </div>
            <div class="h-52" x-data="barChart()" x-init="init()">
                <canvas x-ref="barCanvas" class="w-full h-full"></canvas>
            </div>
        </div>

        {{-- Application Status --}}
        <div class="bg-white dark:bg-neutral-900 rounded-2xl border border-neutral-200 dark:border-neutral-800 p-6">
            <h3 class="text-sm font-bold text-neutral-900 dark:text-white mb-5">Applications by Status</h3>
            @if (count($applicationsByStatus) > 0)
                <div class="h-44 flex items-center justify-center" x-data="doughnutChart()" x-init="init()">
                    <canvas x-ref="doughnutCanvas" class="w-full h-full"></canvas>
                </div>
                <div class="mt-4 pt-4 border-t border-neutral-100 dark:border-neutral-800">
                    <div class="grid grid-cols-2 gap-2">
                        @foreach($applicationsByStatus as $status => $count)
                        <div class="flex items-center gap-2">
                            <div class="w-2.5 h-2.5 rounded-full {{ match($status) { 'applied' => 'bg-blue-500', 'reviewed' => 'bg-indigo-500', 'shortlisted' => 'bg-violet-500', 'interviewed' => 'bg-amber-500', 'offered' => 'bg-green-500', 'rejected' => 'bg-red-500', default => 'bg-neutral-400' } }}"></div>
                            <span class="text-[11px] text-neutral-500 capitalize">{{ str_replace('_', ' ', $status) }}</span>
                            <span class="text-[11px] font-bold text-neutral-700 dark:text-neutral-300 ml-auto">{{ $count }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="h-44 flex flex-col items-center justify-center">
                    <svg class="w-12 h-12 text-neutral-200 dark:text-neutral-700 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    <p class="text-sm text-neutral-400">No applications yet</p>
                    <p class="text-xs text-neutral-300 mt-0.5">Post jobs to start receiving applications</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Recent Jobs --}}
    <div class="bg-white dark:bg-neutral-900 rounded-2xl border border-neutral-200 dark:border-neutral-800 mb-8">
        <div class="flex items-center justify-between px-6 py-4 border-b border-neutral-100 dark:border-neutral-800">
            <h3 class="text-sm font-bold text-neutral-900 dark:text-white flex items-center gap-2">
                <svg class="w-4 h-4 text-[#464d79]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                Recent Job Postings
            </h3>
            <a href="{{ route('recruiter.jobs.index') }}" class="text-xs font-semibold text-[#464d79] hover:underline">View all &rarr;</a>
        </div>
        @if ($recentJobs->isEmpty())
            <div class="p-12 text-center">
                <div class="w-16 h-16 mx-auto mb-4 bg-neutral-100 dark:bg-neutral-800 rounded-full flex items-center justify-center">
                    <svg class="w-7 h-7 text-neutral-300 dark:text-neutral-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <p class="text-sm font-medium text-neutral-500">No jobs posted yet</p>
                <p class="text-xs text-neutral-400 mt-1">Create your first job posting to start attracting talent.</p>
                <a href="{{ route('recruiter.jobs.create') }}" class="inline-flex items-center gap-1.5 mt-4 px-5 py-2.5 text-sm font-bold text-white rounded-xl" style="background: linear-gradient(146deg, #464d79, #4ab098);">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    Post Your First Job
                </a>
            </div>
        @else
            <div class="divide-y divide-neutral-100 dark:divide-neutral-800">
                @foreach ($recentJobs as $job)
                    <a href="{{ route('recruiter.jobs.show', $job) }}" class="flex items-center gap-4 px-6 py-4 hover:bg-neutral-50 dark:hover:bg-neutral-800/50 transition-colors group">
                        <div class="w-10 h-10 rounded-xl bg-neutral-100 dark:bg-neutral-800 flex items-center justify-center shrink-0 group-hover:bg-[#464d79]/10 transition-colors">
                            <svg class="w-5 h-5 text-neutral-400 group-hover:text-[#464d79] transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-neutral-900 dark:text-white truncate group-hover:text-[#464d79] transition-colors">{{ $job->job_title }}</p>
                            <div class="flex items-center gap-3 mt-0.5">
                                <span class="text-xs text-neutral-400 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                    {{ $job->location_city ?? 'Remote' }}
                                </span>
                                <span class="text-xs text-neutral-400">{{ $job->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 shrink-0">
                            <div class="text-center hidden sm:block">
                                <div class="text-sm font-bold text-neutral-900 dark:text-white">{{ $job->applications_count }}</div>
                                <div class="text-[10px] text-neutral-400">applicants</div>
                            </div>
                            @php $statusColor = $job->getDisplayStatusColor(); @endphp
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-semibold
                                {{ $statusColor === 'green' ? 'bg-green-100 text-green-700 dark:bg-green-950/40 dark:text-green-400' : '' }}
                                {{ $statusColor === 'amber' ? 'bg-amber-100 text-amber-700 dark:bg-amber-950/40 dark:text-amber-400' : '' }}
                                {{ $statusColor === 'red' ? 'bg-red-100 text-red-700 dark:bg-red-950/40 dark:text-red-400' : '' }}
                                {{ $statusColor === 'neutral' ? 'bg-neutral-100 text-neutral-600 dark:bg-neutral-800 dark:text-neutral-400' : '' }}
                            ">{{ $job->getDisplayStatus() }}</span>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>

    {{-- News & Events Row --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-4">
        {{-- Latest News --}}
        <div>
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-neutral-900 dark:text-white flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#464d79]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                    Latest News
                </h3>
            </div>
            @if ($news->isEmpty())
                <div class="bg-white dark:bg-neutral-900 rounded-2xl border border-neutral-200 dark:border-neutral-800 p-8 text-center">
                    <p class="text-sm text-neutral-400">No news articles published yet.</p>
                </div>
            @else
                <div class="space-y-3">
                    @foreach ($news as $article)
                        <div class="bg-white dark:bg-neutral-900 rounded-2xl border border-neutral-200 dark:border-neutral-800 overflow-hidden hover:shadow-md transition-shadow group flex">
                            <div class="w-28 shrink-0 bg-neutral-100 dark:bg-neutral-800 relative overflow-hidden">
                                @if ($article->thumbnail_image)
                                    <img src="{{ asset('storage/' . $article->thumbnail_image) }}" alt="" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <svg class="w-8 h-8 text-neutral-300 dark:text-neutral-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                                    </div>
                                @endif
                            </div>
                            <div class="p-4 flex-1 min-w-0">
                                @if ($article->category)
                                    <span class="text-[10px] font-semibold text-[#464d79] uppercase tracking-wide">{{ $article->category->title }}</span>
                                @endif
                                <h4 class="text-sm font-semibold text-neutral-900 dark:text-white leading-snug line-clamp-2 mt-0.5">{{ $article->title }}</h4>
                                <p class="text-[11px] text-neutral-400 mt-1.5">{{ $article->published_at?->format('d M Y') ?? $article->created_at->format('d M Y') }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Upcoming Events --}}
        <div>
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-bold text-neutral-900 dark:text-white flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#464d79]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Upcoming Events
                </h3>
            </div>
            @if ($events->isEmpty())
                <div class="bg-white dark:bg-neutral-900 rounded-2xl border border-neutral-200 dark:border-neutral-800 p-8 text-center">
                    <p class="text-sm text-neutral-400">No events scheduled yet.</p>
                </div>
            @else
                <div class="space-y-3">
                    @foreach ($events as $event)
                        <div class="bg-white dark:bg-neutral-900 rounded-2xl border border-neutral-200 dark:border-neutral-800 p-4 hover:shadow-md transition-shadow flex items-start gap-4">
                            @if ($event->event_date)
                            <div class="w-14 h-14 rounded-xl flex flex-col items-center justify-center shrink-0" style="background: linear-gradient(146deg, rgba(70,77,121,0.08), rgba(74,176,152,0.08));">
                                <span class="text-lg font-bold text-[#464d79] leading-none">{{ $event->event_date->format('d') }}</span>
                                <span class="text-[9px] uppercase text-neutral-500 font-semibold mt-0.5">{{ $event->event_date->format('M') }}</span>
                            </div>
                            @endif
                            <div class="flex-1 min-w-0">
                                @if ($event->event_type)
                                    <span class="text-[10px] font-semibold text-[#4ab098] uppercase tracking-wide capitalize">{{ $event->event_type }}</span>
                                @endif
                                <h4 class="text-sm font-semibold text-neutral-900 dark:text-white leading-snug line-clamp-2 mt-0.5">{{ $event->title }}</h4>
                                <div class="flex items-center gap-3 mt-1.5 text-xs text-neutral-400">
                                    @if ($event->venue)
                                        <span class="flex items-center gap-1 truncate">
                                            <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                            {{ $event->venue }}
                                        </span>
                                    @elseif ($event->online_link)
                                        <span class="flex items-center gap-1 text-blue-500">
                                            <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                            Online
                                        </span>
                                    @endif
                                    @if ($event->event_date)
                                        <span>{{ $event->event_date->format('d M Y') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- Chart.js CDN + Alpine chart components --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        function barChart() {
            return {
                init() {
                    const ctx = this.$refs.barCanvas.getContext('2d');
                    const gradient = ctx.createLinearGradient(0, 0, 0, 200);
                    gradient.addColorStop(0, 'rgba(70, 77, 121, 0.9)');
                    gradient.addColorStop(1, 'rgba(74, 176, 152, 0.9)');

                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: @json($chartLabels),
                            datasets: [{
                                label: 'Jobs Posted',
                                data: @json($chartData),
                                backgroundColor: gradient,
                                borderRadius: 8,
                                borderSkipped: false,
                                barThickness: 32,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: { legend: { display: false } },
                            scales: {
                                y: { beginAtZero: true, ticks: { stepSize: 1, font: { size: 11 }, color: '#a3a3a3' }, grid: { color: 'rgba(0,0,0,0.04)', drawBorder: false } },
                                x: { ticks: { font: { size: 11 }, color: '#a3a3a3' }, grid: { display: false } }
                            }
                        }
                    });
                }
            }
        }

        function doughnutChart() {
            return {
                init() {
                    const raw = @json($applicationsByStatus);
                    const labels = Object.keys(raw).map(s => s.charAt(0).toUpperCase() + s.slice(1).replace('_', ' '));
                    const data = Object.values(raw);
                    const colorMap = {
                        'applied': '#3b82f6', 'reviewed': '#6366f1', 'shortlisted': '#8b5cf6',
                        'interviewed': '#f59e0b', 'offered': '#22c55e', 'rejected': '#ef4444',
                        'pending': '#a3a3a3', 'scheduled': '#14b8a6'
                    };
                    const colors = Object.keys(raw).map(s => colorMap[s] || '#64748b');

                    const ctx = this.$refs.doughnutCanvas.getContext('2d');
                    new Chart(ctx, {
                        type: 'doughnut',
                        data: {
                            labels: labels,
                            datasets: [{ data: data, backgroundColor: colors, borderWidth: 0, spacing: 2 }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            cutout: '70%',
                            plugins: { legend: { display: false } }
                        }
                    });
                }
            }
        }
    </script>
</div>
