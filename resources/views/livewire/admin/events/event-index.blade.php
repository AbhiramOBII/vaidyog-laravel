<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">Events</h1>
        @if(auth('admin')->user()->hasPermission('events.create'))
        <a href="{{ route('admin.events.create') }}" class="px-4 py-2 text-sm font-medium text-white bg-[#464d79] rounded-lg hover:bg-[#3a4166] transition-colors">+ New Event</a>
        @endif
    </div>

    @if (session('success'))
        <div class="p-3 text-sm text-green-700 bg-green-50 border border-green-200 rounded-lg">{{ session('success') }}</div>
    @endif

    {{-- Filters --}}
    <div class="flex flex-wrap gap-3">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search events..." class="px-4 py-2 border border-neutral-300 dark:border-neutral-700 rounded-lg text-sm bg-white dark:bg-neutral-800 dark:text-white w-64">
        <select wire:model.live="status" class="px-3 py-2 border border-neutral-300 dark:border-neutral-700 rounded-lg text-sm bg-white dark:bg-neutral-800 dark:text-white">
            <option value="">All Status</option>
            <option value="draft">Draft</option>
            <option value="published">Published</option>
        </select>
        <select wire:model.live="categoryId" class="px-3 py-2 border border-neutral-300 dark:border-neutral-700 rounded-lg text-sm bg-white dark:bg-neutral-800 dark:text-white">
            <option value="">All Categories</option>
            @foreach ($categories as $id => $name)
                <option value="{{ $id }}">{{ $name }}</option>
            @endforeach
        </select>
        <select wire:model.live="eventType" class="px-3 py-2 border border-neutral-300 dark:border-neutral-700 rounded-lg text-sm bg-white dark:bg-neutral-800 dark:text-white">
            <option value="">All Types</option>
            <option value="online">Online</option>
            <option value="offline">Offline</option>
        </select>
    </div>

    {{-- Table --}}
    <div class="bg-white dark:bg-neutral-900 rounded-xl border border-neutral-200 dark:border-neutral-800 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-neutral-50 dark:bg-neutral-800/50">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-neutral-600 dark:text-neutral-300">Title</th>
                    <th class="px-4 py-3 text-left font-medium text-neutral-600 dark:text-neutral-300">Category</th>
                    <th class="px-4 py-3 text-left font-medium text-neutral-600 dark:text-neutral-300">Type</th>
                    <th class="px-4 py-3 text-left font-medium text-neutral-600 dark:text-neutral-300">Event Date</th>
                    <th class="px-4 py-3 text-left font-medium text-neutral-600 dark:text-neutral-300">Status</th>
                    <th class="px-4 py-3 text-right font-medium text-neutral-600 dark:text-neutral-300">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-100 dark:divide-neutral-800">
                @forelse ($events as $event)
                    <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-800/30">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                @if ($event->thumbnail_image)
                                    <img src="{{ Storage::url($event->thumbnail_image) }}" class="w-10 h-8 rounded object-cover">
                                @else
                                    <div class="w-10 h-8 rounded bg-neutral-100 dark:bg-neutral-700 flex items-center justify-center text-neutral-400 text-xs">E</div>
                                @endif
                                <div>
                                    <p class="font-medium text-neutral-900 dark:text-white">{{ Str::limit($event->title, 45) }}</p>
                                    @if ($event->venue)
                                        <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-0.5">{{ Str::limit($event->venue, 40) }}</p>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-neutral-600 dark:text-neutral-400">{{ $event->category?->title ?? '—' }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-0.5 text-xs font-medium rounded-full {{ $event->event_type === 'online' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400' }}">
                                {{ ucfirst($event->event_type) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-neutral-500 dark:text-neutral-400">{{ $event->event_date?->format('d M Y, h:i A') ?? '—' }}</td>
                        <td class="px-4 py-3">
                            <button wire:click="toggleStatus({{ $event->id }})" class="px-2 py-0.5 text-xs font-medium rounded-full {{ $event->status === 'published' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400' }}">
                                {{ ucfirst($event->status) }}
                            </button>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex items-center justify-end gap-2">
                                @if(auth('admin')->user()->hasPermission('events.edit'))
                                <a href="{{ route('admin.events.edit', $event->id) }}" class="px-2 py-1 text-xs font-medium text-[#464d79] hover:bg-[#464d79]/10 rounded transition-colors">Edit</a>
                                @endif
                                @if(auth('admin')->user()->hasPermission('events.delete'))
                                <button wire:click="delete({{ $event->id }})" wire:confirm="Delete this event?" class="px-2 py-1 text-xs font-medium text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded transition-colors">Delete</button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-neutral-500">No events found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $events->links() }}</div>
</div>
