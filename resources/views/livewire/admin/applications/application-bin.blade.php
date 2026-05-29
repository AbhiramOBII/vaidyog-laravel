<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">Application Bin</h1>
            <p class="text-sm text-neutral-500 mt-1">Soft-deleted applications that can be restored.</p>
        </div>
    </div>

    @if(session('message'))
    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">{{ session('message') }}</div>
    @endif

    <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-neutral-50 dark:bg-neutral-900 border-b border-neutral-200 dark:border-neutral-700">
                <tr>
                    <th class="text-left px-4 py-3 font-medium text-neutral-600 dark:text-neutral-400">Applicant</th>
                    <th class="text-left px-4 py-3 font-medium text-neutral-600 dark:text-neutral-400">Job</th>
                    <th class="text-left px-4 py-3 font-medium text-neutral-600 dark:text-neutral-400">Deleted At</th>
                    <th class="text-right px-4 py-3 font-medium text-neutral-600 dark:text-neutral-400">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-100 dark:divide-neutral-700">
                @forelse($applications as $app)
                <tr>
                    <td class="px-4 py-3 text-neutral-900 dark:text-white">{{ $app->applicant?->name ?? 'N/A' }}</td>
                    <td class="px-4 py-3 text-neutral-700 dark:text-neutral-300">{{ $app->job?->job_title ?? 'Deleted' }}</td>
                    <td class="px-4 py-3 text-neutral-500 text-xs">{{ $app->deleted_at->format('M d, Y H:i') }}</td>
                    <td class="px-4 py-3 text-right">
                        <button wire:click="restore('{{ $app->id }}')" wire:confirm="Restore this application?" class="text-[#4ab098] hover:underline text-xs font-medium">Restore</button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-4 py-8 text-center text-neutral-500">Bin is empty.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>{{ $applications->links() }}</div>
</div>
