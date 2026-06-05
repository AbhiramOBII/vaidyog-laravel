<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">Event Categories</h1>
        @if(auth('admin')->user()->hasPermission('events.create'))
        <a href="{{ route('admin.event-categories.create') }}" class="px-4 py-2 text-sm font-medium text-white bg-[#464d79] rounded-lg hover:bg-[#3a4166] transition-colors">+ New Category</a>
        @endif
    </div>

    @if (session('success'))
        <div class="p-3 text-sm text-green-700 bg-green-50 border border-green-200 rounded-lg">{{ session('success') }}</div>
    @endif

    {{-- Filters --}}
    <div class="flex flex-wrap gap-3">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search categories..." class="px-4 py-2 border border-neutral-300 dark:border-neutral-700 rounded-lg text-sm bg-white dark:bg-neutral-800 dark:text-white w-64">
        <select wire:model.live="status" class="px-3 py-2 border border-neutral-300 dark:border-neutral-700 rounded-lg text-sm bg-white dark:bg-neutral-800 dark:text-white">
            <option value="">All Status</option>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
        </select>
    </div>

    {{-- Table --}}
    <div class="bg-white dark:bg-neutral-900 rounded-xl border border-neutral-200 dark:border-neutral-800 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-neutral-50 dark:bg-neutral-800/50">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-neutral-600 dark:text-neutral-300">Title</th>
                    <th class="px-4 py-3 text-left font-medium text-neutral-600 dark:text-neutral-300">Parent</th>
                    <th class="px-4 py-3 text-left font-medium text-neutral-600 dark:text-neutral-300">Status</th>
                    <th class="px-4 py-3 text-left font-medium text-neutral-600 dark:text-neutral-300">Created</th>
                    <th class="px-4 py-3 text-right font-medium text-neutral-600 dark:text-neutral-300">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-100 dark:divide-neutral-800">
                @forelse ($categories as $cat)
                    <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-800/30">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                @if ($cat->thumbnail_image)
                                    <img src="{{ Storage::url($cat->thumbnail_image) }}" class="w-8 h-8 rounded object-cover">
                                @else
                                    <div class="w-8 h-8 rounded bg-neutral-100 dark:bg-neutral-700 flex items-center justify-center text-neutral-400 text-xs">E</div>
                                @endif
                                <span class="font-medium text-neutral-900 dark:text-white">{{ $cat->title }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-neutral-600 dark:text-neutral-400">{{ $cat->parent?->title ?? '—' }}</td>
                        <td class="px-4 py-3">
                            <button wire:click="toggleStatus({{ $cat->id }})" class="px-2 py-0.5 text-xs font-medium rounded-full {{ $cat->status === 'active' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-neutral-100 text-neutral-600 dark:bg-neutral-700 dark:text-neutral-400' }}">
                                {{ ucfirst($cat->status) }}
                            </button>
                        </td>
                        <td class="px-4 py-3 text-neutral-500 dark:text-neutral-400">{{ $cat->created_at->format('d M Y') }}</td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex items-center justify-end gap-2">
                                @if(auth('admin')->user()->hasPermission('events.edit'))
                                <a href="{{ route('admin.event-categories.edit', $cat->id) }}" class="px-2 py-1 text-xs font-medium text-[#464d79] hover:bg-[#464d79]/10 rounded transition-colors">Edit</a>
                                @endif
                                @if(auth('admin')->user()->hasPermission('events.delete'))
                                <button wire:click="delete({{ $cat->id }})" wire:confirm="Delete this category? All events under it will also be deleted." class="px-2 py-1 text-xs font-medium text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded transition-colors">Delete</button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-neutral-500">No categories found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $categories->links() }}</div>
</div>
