<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">Application Detail</h1>
            <p class="text-sm text-neutral-500 mt-1">{{ $application->applicant?->name }} → {{ $application->job?->job_title }}</p>
        </div>
        <a href="{{ route('admin.applications.index') }}" wire:navigate class="text-sm text-[#464d79] hover:underline">&larr; Back</a>
    </div>

    {{-- Status & Ranking --}}
    <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
        <div class="flex items-center gap-4">
            <span class="inline-flex px-3 py-1 rounded-full text-sm font-medium {{ $application->status->getBadgeClasses() }}">{{ $application->status->label() }}</span>
            <span class="inline-flex px-2 py-0.5 rounded text-xs font-bold {{ $application->ranking->getBadgeClasses() }}">{{ $application->ranking->value }} — {{ $application->ranking->getLabel() }}</span>
        </div>
    </div>

    {{-- Application Info --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
            <h3 class="text-sm font-semibold text-neutral-500 uppercase mb-3">Applicant</h3>
            <p class="text-neutral-900 dark:text-white font-medium">{{ $application->applicant?->name }}</p>
            <p class="text-sm text-neutral-500">{{ $application->applicant?->email }}</p>
            <p class="text-sm text-neutral-500">{{ $application->applicant?->phone }}</p>
        </div>
        <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
            <h3 class="text-sm font-semibold text-neutral-500 uppercase mb-3">Job</h3>
            <p class="text-neutral-900 dark:text-white font-medium">{{ $application->job?->job_title }}</p>
            <p class="text-sm text-neutral-500">{{ $application->job?->institution_name }}</p>
            <p class="text-sm text-neutral-500">Applied: {{ $application->applied_at->format('M d, Y H:i') }}</p>
        </div>
    </div>

    {{-- Cover Note --}}
    @if($application->cover_note)
    <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
        <h3 class="text-sm font-semibold text-neutral-500 uppercase mb-3">Cover Note</h3>
        <p class="text-neutral-700 dark:text-neutral-300 text-sm">{{ $application->cover_note }}</p>
    </div>
    @endif

    {{-- Status Timeline --}}
    <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
        <h3 class="text-sm font-semibold text-neutral-500 uppercase mb-3">Status History</h3>
        <div class="space-y-3">
            @foreach($application->getStatusHistory() as $status => $timestamp)
            <div class="flex items-center gap-3">
                <div class="w-2.5 h-2.5 rounded-full bg-[#4ab098]"></div>
                <span class="text-sm font-medium text-neutral-900 dark:text-white capitalize">{{ $status }}</span>
                <span class="text-xs text-neutral-500">{{ \Carbon\Carbon::parse($timestamp)->format('M d, Y H:i') }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>
