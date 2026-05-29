<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">Applicants for: {{ $job->job_title }}</h1>
            <p class="text-sm text-neutral-500 mt-1">{{ $stats['total'] }} total applications</p>
        </div>
        <a href="{{ route('recruiter.jobs.show', $job) }}" wire:navigate class="text-sm text-[#464d79] hover:underline">&larr; Back to Job</a>
    </div>

    @if(session('message'))
    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">{{ session('message') }}</div>
    @endif
    @if(session('error'))
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">{{ session('error') }}</div>
    @endif

    {{-- Stats bar --}}
    <div class="grid grid-cols-3 sm:grid-cols-7 gap-3">
        @foreach(['applied' => 'blue', 'reviewed' => 'indigo', 'shortlisted' => 'violet', 'interviewed' => 'amber', 'offered' => 'green', 'rejected' => 'red'] as $key => $color)
        <div class="bg-white dark:bg-neutral-800 rounded-lg border border-neutral-200 dark:border-neutral-700 p-3 text-center">
            <p class="text-lg font-bold text-{{ $color }}-600">{{ $stats[$key] }}</p>
            <p class="text-xs text-neutral-500 capitalize">{{ $key }}</p>
        </div>
        @endforeach
        <div class="bg-white dark:bg-neutral-800 rounded-lg border border-neutral-200 dark:border-neutral-700 p-3 text-center">
            <p class="text-lg font-bold text-neutral-900 dark:text-white">{{ $stats['total'] }}</p>
            <p class="text-xs text-neutral-500">Total</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-4">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search applicant name..." class="h-10 px-3 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 rounded-lg text-sm focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20"/>
            <select wire:model.live="status" class="h-10 px-3 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 rounded-lg text-sm">
                <option value="">All Statuses</option>
                @foreach($statuses as $s)
                    <option value="{{ $s->value }}">{{ $s->label() }}</option>
                @endforeach
            </select>
            <select wire:model.live="ranking" class="h-10 px-3 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 rounded-lg text-sm">
                <option value="">All Rankings</option>
                @foreach($rankings as $r)
                    <option value="{{ $r->value }}">{{ $r->value }} — {{ $r->getLabel() }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-neutral-50 dark:bg-neutral-900 border-b border-neutral-200 dark:border-neutral-700">
                <tr>
                    <th class="text-center px-3 py-3 font-medium text-neutral-600 dark:text-neutral-400">Rank</th>
                    <th class="text-left px-4 py-3 font-medium text-neutral-600 dark:text-neutral-400">Applicant</th>
                    <th class="text-left px-4 py-3 font-medium text-neutral-600 dark:text-neutral-400">Category</th>
                    <th class="text-center px-4 py-3 font-medium text-neutral-600 dark:text-neutral-400">Skills Match</th>
                    <th class="text-center px-4 py-3 font-medium text-neutral-600 dark:text-neutral-400">Status</th>
                    <th class="text-left px-4 py-3 font-medium text-neutral-600 dark:text-neutral-400">Applied</th>
                    <th class="text-right px-4 py-3 font-medium text-neutral-600 dark:text-neutral-400">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-100 dark:divide-neutral-700">
                @forelse($applications as $app)
                <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-900/50">
                    <td class="px-3 py-3 text-center"><span class="inline-flex px-2 py-0.5 rounded text-xs font-bold {{ $app->ranking->getBadgeClasses() }}">{{ $app->ranking->value }}</span></td>
                    <td class="px-4 py-3">
                        <p class="font-medium text-neutral-900 dark:text-white">{{ $app->applicant?->name }}</p>
                        <p class="text-xs text-neutral-500">{{ $app->applicant?->phone }}</p>
                    </td>
                    <td class="px-4 py-3 text-neutral-500 text-xs">{{ $app->applicant?->jobSeekerProfile?->category_name ?? '—' }}</td>
                    <td class="px-4 py-3 text-center">
                        @if($app->matching_skills)
                        <span class="text-xs font-medium text-[#4ab098]">{{ count($app->matching_skills) }}/{{ count($job->key_skills ?? []) }}</span>
                        @else
                        <span class="text-xs text-neutral-400">—</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-center"><span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $app->status->getBadgeClasses() }}">{{ $app->status->label() }}</span></td>
                    <td class="px-4 py-3 text-neutral-500 text-xs">{{ $app->applied_at->format('M d') }}</td>
                    <td class="px-4 py-3 text-right space-x-2">
                        <a href="{{ route('recruiter.applications.show', $app) }}" wire:navigate class="text-[#464d79] hover:underline text-xs font-medium">View</a>
                        @php $allowed = $transitionService->getAllowedNextStatuses($app->status->value); @endphp
                        @if(!empty($allowed))
                        <select wire:change="updateStatus('{{ $app->id }}', $event.target.value)" class="inline-block h-7 px-2 text-xs border border-neutral-200 dark:border-neutral-700 rounded bg-neutral-50 dark:bg-neutral-900">
                            <option value="">Update →</option>
                            @foreach($allowed as $next)
                            <option value="{{ $next }}">{{ ucfirst($next) }}</option>
                            @endforeach
                        </select>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-4 py-8 text-center text-neutral-500">No applicants yet for this job.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>{{ $applications->links() }}</div>
</div>
