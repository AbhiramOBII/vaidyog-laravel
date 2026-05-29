<div>
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div class="flex items-center gap-3">
            <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">Job Bin</h1>
            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-neutral-100 text-neutral-600 dark:bg-neutral-700 dark:text-neutral-400">{{ $totalCount }}</span>
        </div>
        @if($totalCount > 0)
        <button wire:click="emptyBin" wire:confirm="Permanently delete ALL jobs in the bin? This cannot be undone." class="h-9 px-4 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">Empty Bin</button>
        @endif
    </div>

    @if(session('success'))
    <div class="mb-4 p-3 rounded-lg bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-sm text-green-700 dark:text-green-300">{{ session('success') }}</div>
    @endif

    {{-- Search --}}
    <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-4 mb-6">
        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search deleted job title..." class="w-full sm:w-80 h-10 px-4 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-600 rounded-lg text-sm focus:outline-none focus:border-[#464d79]"/>
    </div>

    {{-- Table --}}
    <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-neutral-50 dark:bg-neutral-900 border-b border-neutral-200 dark:border-neutral-700">
                    <tr>
                        <th class="text-left px-4 py-3 font-medium text-neutral-600 dark:text-neutral-400">Job Title</th>
                        <th class="text-left px-4 py-3 font-medium text-neutral-600 dark:text-neutral-400">Institution</th>
                        <th class="text-left px-4 py-3 font-medium text-neutral-600 dark:text-neutral-400">Deleted By</th>
                        <th class="text-left px-4 py-3 font-medium text-neutral-600 dark:text-neutral-400">Deleted At</th>
                        <th class="text-right px-4 py-3 font-medium text-neutral-600 dark:text-neutral-400">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100 dark:divide-neutral-700">
                    @forelse($bins as $bin)
                    <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-900/50">
                        <td class="px-4 py-3 font-medium text-neutral-900 dark:text-white">{{ Str::limit($bin->original_data['job_title'] ?? 'Unknown', 40) }}</td>
                        <td class="px-4 py-3 text-neutral-600 dark:text-neutral-400">{{ Str::limit($bin->original_data['institution_name'] ?? '-', 25) }}</td>
                        <td class="px-4 py-3">
                            <span class="text-xs px-2 py-0.5 rounded-full {{ $bin->deleted_by_type === 'admin' ? 'bg-[#464d79]/10 text-[#464d79]' : 'bg-neutral-100 text-neutral-600' }}">{{ ucfirst($bin->deleted_by_type) }}</span>
                        </td>
                        <td class="px-4 py-3 text-neutral-500 text-xs">{{ $bin->created_at->format('M d, Y H:i') }}</td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button wire:click="restore('{{ $bin->id }}')" class="h-7 px-3 rounded text-xs font-medium bg-green-100 text-green-700 hover:bg-green-200 transition-colors">Restore</button>
                                <button wire:click="permanentDelete('{{ $bin->id }}')" wire:confirm="Permanently delete this job? Cannot be undone." class="h-7 px-3 rounded text-xs font-medium bg-red-100 text-red-700 hover:bg-red-200 transition-colors">Delete Forever</button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-16 text-center">
                            <div class="flex flex-col items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-neutral-200 dark:text-neutral-700 mb-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                <p class="text-sm font-medium text-neutral-700 dark:text-neutral-300">Bin is empty</p>
                                <p class="text-xs text-neutral-500 mt-1">Deleted jobs will appear here.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($bins->hasPages())
        <div class="px-4 py-3 border-t border-neutral-200 dark:border-neutral-700">{{ $bins->links() }}</div>
        @endif
    </div>
</div>
