<div>
    {{-- Header --}}
    <div class="flex items-center gap-3 mb-6">
        <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">Pending Job Approvals</h1>
        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400">{{ $pendingCount }}</span>
    </div>

    @if(session('success'))
    <div class="mb-4 p-3 rounded-lg bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-sm text-green-700 dark:text-green-300">{{ session('success') }}</div>
    @endif

    {{-- Filters --}}
    <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-4 mb-4">
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search job title or institution..." class="w-full sm:w-80 h-10 px-4 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-600 rounded-lg text-sm focus:outline-none focus:border-[#464d79]"/>
    </div>

    {{-- Bulk actions bar --}}
    @if(count($selected) > 0)
    <div class="mb-4 p-3 rounded-lg bg-[#464d79]/10 border border-[#464d79]/20 flex items-center gap-3">
        <span class="text-sm font-medium text-[#464d79] dark:text-indigo-300">{{ count($selected) }} selected</span>
        <button wire:click="bulkApprove" class="h-8 px-3 bg-green-600 hover:bg-green-700 text-white text-xs font-semibold rounded-lg transition-colors">Approve Selected</button>
        <button wire:click="openBulkReject" class="h-8 px-3 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold rounded-lg transition-colors">Reject Selected</button>
        <button wire:click="$set('selected', [])" class="text-xs text-neutral-500 hover:text-neutral-700 ml-auto">Clear</button>
    </div>
    @endif

    {{-- Table --}}
    <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-neutral-50 dark:bg-neutral-900 border-b border-neutral-200 dark:border-neutral-700">
                    <tr>
                        <th class="w-10 px-4 py-3"><input type="checkbox" wire:model.live="selected" class="sr-only"/></th>
                        <th class="text-left px-4 py-3 font-medium text-neutral-600 dark:text-neutral-400">Job Title</th>
                        <th class="text-left px-4 py-3 font-medium text-neutral-600 dark:text-neutral-400">Institution</th>
                        <th class="text-left px-4 py-3 font-medium text-neutral-600 dark:text-neutral-400">Category</th>
                        <th class="text-left px-4 py-3 font-medium text-neutral-600 dark:text-neutral-400">Type</th>
                        <th class="text-center px-4 py-3 font-medium text-neutral-600 dark:text-neutral-400">Vacancies</th>
                        <th class="text-left px-4 py-3 font-medium text-neutral-600 dark:text-neutral-400">Submitted</th>
                        <th class="text-right px-4 py-3 font-medium text-neutral-600 dark:text-neutral-400">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100 dark:divide-neutral-700">
                    @forelse($jobs as $job)
                    <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-900/50">
                        <td class="px-4 py-3"><input type="checkbox" wire:model.live="selected" value="{{ $job->id }}" class="w-4 h-4 rounded border-neutral-300 text-[#464d79] focus:ring-[#464d79]"/></td>
                        <td class="px-4 py-3 font-medium text-neutral-900 dark:text-white">{{ Str::limit($job->job_title, 35) }}</td>
                        <td class="px-4 py-3 text-neutral-600 dark:text-neutral-400">{{ Str::limit($job->institution_name, 25) }}</td>
                        <td class="px-4 py-3 text-neutral-500 text-xs">{{ $job->category_slug }}</td>
                        <td class="px-4 py-3"><span class="text-xs px-2 py-0.5 rounded-full bg-neutral-100 dark:bg-neutral-700">{{ $job->employment_type->label() }}</span></td>
                        <td class="px-4 py-3 text-center">{{ $job->number_of_vacancies }}</td>
                        <td class="px-4 py-3 text-neutral-500 text-xs">{{ $job->created_at->diffForHumans() }}</td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                <a href="{{ route('admin.jobs.show', $job) }}" wire:navigate class="h-7 px-2.5 inline-flex items-center rounded text-xs font-medium bg-neutral-100 dark:bg-neutral-700 text-neutral-600 dark:text-neutral-300 hover:bg-neutral-200">View</a>
                                <button wire:click="approve('{{ $job->id }}')" class="h-7 px-2.5 rounded text-xs font-medium bg-green-100 text-green-700 hover:bg-green-200 transition-colors">Approve</button>
                                <button wire:click="startReject('{{ $job->id }}')" class="h-7 px-2.5 rounded text-xs font-medium bg-red-100 text-red-700 hover:bg-red-200 transition-colors">Reject</button>
                            </div>
                        </td>
                    </tr>
                    {{-- Inline rejection form --}}
                    @if($rejectingJobId === $job->id)
                    <tr class="bg-red-50/50 dark:bg-red-900/10">
                        <td colspan="8" class="px-4 py-3">
                            <div class="flex items-start gap-3">
                                <textarea wire:model="rejectionReason" rows="2" placeholder="Rejection reason (min 10 chars)..." class="flex-1 px-3 py-2 bg-white dark:bg-neutral-900 border border-red-200 dark:border-red-800 rounded-lg text-sm focus:outline-none focus:border-red-400"></textarea>
                                <button wire:click="confirmReject" class="h-9 px-4 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold rounded-lg transition-colors">Confirm Reject</button>
                                <button wire:click="cancelReject" class="h-9 px-3 text-xs text-neutral-500 hover:text-neutral-700">Cancel</button>
                            </div>
                            @error('rejectionReason') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </td>
                    </tr>
                    @endif
                    @empty
                    <tr>
                        <td colspan="8" class="px-4 py-16 text-center">
                            <div class="flex flex-col items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-green-300 mb-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                <p class="text-sm font-medium text-neutral-700 dark:text-neutral-300">All caught up!</p>
                                <p class="text-xs text-neutral-500 mt-1">No jobs are pending approval.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($jobs->hasPages())
        <div class="px-4 py-3 border-t border-neutral-200 dark:border-neutral-700">{{ $jobs->links() }}</div>
        @endif
    </div>

    {{-- Bulk reject modal --}}
    @if($showBulkRejectModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" x-data x-transition>
        <div class="bg-white dark:bg-neutral-800 rounded-xl shadow-xl w-full max-w-md p-6">
            <h3 class="text-lg font-bold text-neutral-900 dark:text-white mb-2">Reject {{ count($selected) }} Jobs</h3>
            <p class="text-sm text-neutral-500 mb-4">Provide a shared rejection reason for all selected jobs.</p>
            <textarea wire:model="bulkRejectionReason" rows="3" placeholder="Rejection reason (min 10 chars)..." class="w-full px-3 py-2 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-600 rounded-lg text-sm focus:outline-none focus:border-red-400 mb-2"></textarea>
            @error('bulkRejectionReason') <p class="text-xs text-red-500 mb-2">{{ $message }}</p> @enderror
            <div class="flex justify-end gap-2">
                <button wire:click="$set('showBulkRejectModal', false)" class="h-9 px-4 text-sm text-neutral-600 hover:bg-neutral-100 rounded-lg">Cancel</button>
                <button wire:click="confirmBulkReject" class="h-9 px-4 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-lg">Reject All</button>
            </div>
        </div>
    </div>
    @endif
</div>
