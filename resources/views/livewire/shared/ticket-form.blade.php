<div class="max-w-2xl mx-auto space-y-6">
    <h2 class="text-xl font-bold text-neutral-900 dark:text-white">Support Tickets</h2>

    @if (session('success'))
        <div class="p-3 text-sm text-green-700 bg-green-50 border border-green-200 rounded-lg">{{ session('success') }}</div>
    @endif

    {{-- New Ticket Form --}}
    <form wire:submit="submit" class="bg-white dark:bg-neutral-900 rounded-xl border border-neutral-200 dark:border-neutral-800 p-6 space-y-4">
        <h3 class="text-sm font-semibold text-neutral-700 dark:text-neutral-300">Raise a New Ticket</h3>
        <div>
            <label class="block text-xs font-medium text-neutral-600 dark:text-neutral-400 mb-1">Title *</label>
            <input type="text" wire:model="title" maxlength="200" class="w-full px-3 py-2.5 border border-neutral-300 dark:border-neutral-700 rounded-lg text-sm bg-white dark:bg-neutral-800 dark:text-white" placeholder="Brief summary of your issue...">
            @error('title') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-xs font-medium text-neutral-600 dark:text-neutral-400 mb-1">Description *</label>
            <textarea wire:model="description" rows="4" maxlength="5000" class="w-full px-3 py-2.5 border border-neutral-300 dark:border-neutral-700 rounded-lg text-sm bg-white dark:bg-neutral-800 dark:text-white resize-none" placeholder="Describe your issue in detail..."></textarea>
            @error('description') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>
        <div class="flex justify-end">
            <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-[#464d79] rounded-lg hover:bg-[#3a4166] transition-colors">Submit Ticket</button>
        </div>
    </form>

    {{-- My Tickets --}}
    @if ($tickets->count() > 0)
        <div class="bg-white dark:bg-neutral-900 rounded-xl border border-neutral-200 dark:border-neutral-800 overflow-hidden">
            <div class="px-6 py-4 border-b border-neutral-100 dark:border-neutral-800">
                <h3 class="text-sm font-semibold text-neutral-700 dark:text-neutral-300">My Recent Tickets</h3>
            </div>
            <div class="divide-y divide-neutral-100 dark:divide-neutral-800">
                @foreach ($tickets as $ticket)
                    <div class="px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-neutral-900 dark:text-white">#{{ $ticket->id }} — {{ $ticket->title }}</p>
                                <p class="text-xs text-neutral-500 mt-0.5">{{ $ticket->created_at->format('d M Y, h:i A') }}</p>
                            </div>
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
                        </div>
                        @if ($ticket->comments && count($ticket->comments) > 0)
                            <div class="mt-2 p-2 bg-neutral-50 dark:bg-neutral-800 rounded text-xs text-neutral-600 dark:text-neutral-400">
                                Latest reply: {{ Str::limit(end($ticket->comments)['message'] ?? '', 80) }}
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
