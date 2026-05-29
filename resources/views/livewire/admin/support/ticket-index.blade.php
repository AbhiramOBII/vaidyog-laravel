<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">Support Tickets</h1>
    </div>

    {{-- Filters --}}
    <div class="flex flex-wrap gap-3">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search by title or user..." class="px-4 py-2 border border-neutral-300 dark:border-neutral-700 rounded-lg text-sm bg-white dark:bg-neutral-800 dark:text-white w-64">
        <select wire:model.live="status" class="px-3 py-2 border border-neutral-300 dark:border-neutral-700 rounded-lg text-sm bg-white dark:bg-neutral-800 dark:text-white">
            <option value="">All Status</option>
            <option value="raised">Raised</option>
            <option value="in-progress">In Progress</option>
            <option value="resolved">Resolved</option>
            <option value="closed">Closed</option>
        </select>
    </div>

    {{-- Table --}}
    <div class="bg-white dark:bg-neutral-900 rounded-xl border border-neutral-200 dark:border-neutral-800 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-neutral-50 dark:bg-neutral-800/50">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-neutral-600 dark:text-neutral-300">#</th>
                    <th class="px-4 py-3 text-left font-medium text-neutral-600 dark:text-neutral-300">Title</th>
                    <th class="px-4 py-3 text-left font-medium text-neutral-600 dark:text-neutral-300">User</th>
                    <th class="px-4 py-3 text-left font-medium text-neutral-600 dark:text-neutral-300">Status</th>
                    <th class="px-4 py-3 text-left font-medium text-neutral-600 dark:text-neutral-300">Raised</th>
                    <th class="px-4 py-3 text-right font-medium text-neutral-600 dark:text-neutral-300">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-100 dark:divide-neutral-800">
                @forelse ($tickets as $ticket)
                    <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-800/30">
                        <td class="px-4 py-3 text-neutral-500 dark:text-neutral-400">#{{ $ticket->id }}</td>
                        <td class="px-4 py-3">
                            <p class="font-medium text-neutral-900 dark:text-white">{{ Str::limit($ticket->title, 50) }}</p>
                            <p class="text-xs text-neutral-500 mt-0.5">{{ Str::limit($ticket->description, 60) }}</p>
                        </td>
                        <td class="px-4 py-3">
                            <p class="text-neutral-700 dark:text-neutral-300">{{ $ticket->user?->name ?? '—' }}</p>
                            <p class="text-xs text-neutral-500">{{ $ticket->user?->email ?? '' }}</p>
                        </td>
                        <td class="px-4 py-3">
                            @php
                                $statusColors = [
                                    'raised' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
                                    'in-progress' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                                    'resolved' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                                    'closed' => 'bg-neutral-100 text-neutral-600 dark:bg-neutral-700 dark:text-neutral-400',
                                ];
                            @endphp
                            <span class="px-2 py-0.5 text-xs font-medium rounded-full {{ $statusColors[$ticket->status] ?? '' }}">
                                {{ ucfirst($ticket->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-neutral-500 dark:text-neutral-400 text-xs">{{ $ticket->created_at->format('d M Y') }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.support-tickets.show', $ticket->id) }}" class="px-2 py-1 text-xs font-medium text-[#464d79] hover:bg-[#464d79]/10 rounded transition-colors">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-neutral-500">No tickets found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $tickets->links() }}</div>
</div>
