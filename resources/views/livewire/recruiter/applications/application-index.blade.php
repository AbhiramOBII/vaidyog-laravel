<div class="space-y-6">
    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">All Applicants</h1>
        <p class="text-sm text-neutral-500 mt-1">Manage applications across all your active job postings.</p>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
        <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-4 text-center">
            <p class="text-2xl font-bold text-neutral-900 dark:text-white">{{ $stats['total'] }}</p>
            <p class="text-xs text-neutral-500 mt-1">Total</p>
        </div>
        <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-4 text-center">
            <p class="text-2xl font-bold text-blue-600">{{ $stats['new'] }}</p>
            <p class="text-xs text-neutral-500 mt-1">New</p>
        </div>
        <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-4 text-center">
            <p class="text-2xl font-bold text-violet-600">{{ $stats['shortlisted'] }}</p>
            <p class="text-xs text-neutral-500 mt-1">Shortlisted</p>
        </div>
        <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-4 text-center">
            <p class="text-2xl font-bold text-amber-600">{{ $stats['interviewed'] }}</p>
            <p class="text-xs text-neutral-500 mt-1">Interviewed</p>
        </div>
        <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-4 text-center">
            <p class="text-2xl font-bold text-green-600">{{ $stats['offered'] }}</p>
            <p class="text-xs text-neutral-500 mt-1">Offered</p>
        </div>
        <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-4 text-center">
            <p class="text-2xl font-bold text-red-600">{{ $stats['rejected'] }}</p>
            <p class="text-xs text-neutral-500 mt-1">Rejected</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-4">
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-3">
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search applicant or job..." class="h-10 px-3 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 rounded-lg text-sm focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20"/>
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
            <select wire:model.live="jobFilter" class="h-10 px-3 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 rounded-lg text-sm">
                <option value="">All Jobs</option>
            </select>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-neutral-50 dark:bg-neutral-900 border-b border-neutral-200 dark:border-neutral-700">
                <tr>
                    <th class="text-left px-4 py-3 font-medium text-neutral-600 dark:text-neutral-400">Applicant</th>
                    <th class="text-left px-4 py-3 font-medium text-neutral-600 dark:text-neutral-400">Job</th>
                    <th class="text-center px-4 py-3 font-medium text-neutral-600 dark:text-neutral-400">Rank</th>
                    <th class="text-center px-4 py-3 font-medium text-neutral-600 dark:text-neutral-400">Status</th>
                    <th class="text-left px-4 py-3 font-medium text-neutral-600 dark:text-neutral-400">Applied</th>
                    <th class="text-right px-4 py-3 font-medium text-neutral-600 dark:text-neutral-400">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-100 dark:divide-neutral-700">
                @forelse($applications as $app)
                <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-900/50">
                    <td class="px-4 py-3 font-medium text-neutral-900 dark:text-white">{{ $app->applicant?->name ?? 'N/A' }}</td>
                    <td class="px-4 py-3 text-neutral-700 dark:text-neutral-300">{{ Str::limit($app->job?->job_title, 30) }}</td>
                    <td class="px-4 py-3 text-center"><span class="inline-flex px-2 py-0.5 rounded text-xs font-bold {{ $app->ranking->getBadgeClasses() }}">{{ $app->ranking->value }}</span></td>
                    <td class="px-4 py-3 text-center"><span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium {{ $app->status->getBadgeClasses() }}">{{ $app->status->label() }}</span></td>
                    <td class="px-4 py-3 text-neutral-500 text-xs">{{ $app->applied_at->format('M d, Y') }}</td>
                    <td class="px-4 py-3 text-right">
                        <a href="{{ route('recruiter.applications.show', $app) }}" wire:navigate class="text-[#464d79] hover:underline text-xs font-medium">View</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-4 py-8 text-center text-neutral-500">No applications yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>{{ $applications->links() }}</div>
</div>
