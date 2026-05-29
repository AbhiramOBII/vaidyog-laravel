<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">Saved Jobs</h1>
        <p class="text-sm text-neutral-500 mt-1">Jobs you've bookmarked for later.</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($savedJobs as $saved)
        @php $job = $saved->job; @endphp
        @if($job)
        <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-5">
            <div class="flex items-start gap-3">
                <img src="{{ $job->getThumbnailUrl() }}" alt="{{ $job->job_title }}" class="w-10 h-10 rounded-lg object-cover shrink-0"/>
                <div class="flex-1 min-w-0">
                    <h3 class="font-semibold text-neutral-900 dark:text-white text-sm truncate">{{ $job->job_title }}</h3>
                    <p class="text-xs text-neutral-500 mt-0.5">{{ $job->institution_name }}</p>
                    <p class="text-xs text-neutral-400 mt-0.5">{{ $job->location_city }} &middot; {{ $job->employment_type?->label() }}</p>
                </div>
            </div>
            <div class="flex items-center gap-2 mt-4">
                <a href="{{ route('jobs.show', $job) }}" wire:navigate class="flex-1 text-center px-3 h-8 flex items-center justify-center text-xs font-medium text-[#464d79] border border-[#464d79]/20 rounded-lg hover:bg-[#464d79]/5">View Job</a>
                <button wire:click="unsave({{ $saved->id }})" class="px-3 h-8 text-xs font-medium text-red-600 border border-red-200 rounded-lg hover:bg-red-50">Remove</button>
            </div>
        </div>
        @endif
        @empty
        <div class="col-span-full bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-12 text-center">
            <p class="text-neutral-500">No saved jobs yet. Browse jobs and save the ones you're interested in.</p>
            <a href="{{ route('jobs.index') }}" wire:navigate class="mt-3 inline-flex px-4 h-9 items-center bg-[#464d79] text-white text-sm font-medium rounded-lg hover:bg-[#464d79]/90">Browse Jobs</a>
        </div>
        @endforelse
    </div>

    <div>{{ $savedJobs->links() }}</div>
</div>
