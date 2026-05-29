<div class="max-w-4xl mx-auto space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">Ticket #{{ $ticket->id }}</h1>
            <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-1">{{ $ticket->title }}</p>
        </div>
        <a href="{{ route('admin.support-tickets.index') }}" class="px-4 py-2 text-sm font-medium text-neutral-700 dark:text-neutral-300 bg-white dark:bg-neutral-800 border border-neutral-300 dark:border-neutral-700 rounded-lg hover:bg-neutral-50 dark:hover:bg-neutral-700">Back to List</a>
    </div>

    {{-- Ticket Info --}}
    <div class="bg-white dark:bg-neutral-900 rounded-xl border border-neutral-200 dark:border-neutral-800 p-6 space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <p class="text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase">User</p>
                <p class="text-sm font-medium text-neutral-900 dark:text-white mt-1">{{ $ticket->user?->name ?? '—' }}</p>
                <p class="text-xs text-neutral-500">{{ $ticket->user?->email ?? '' }}</p>
            </div>
            <div>
                <p class="text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase">Current Status</p>
                @php
                    $statusColors = [
                        'raised' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
                        'in-progress' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                        'resolved' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                        'closed' => 'bg-neutral-100 text-neutral-600 dark:bg-neutral-700 dark:text-neutral-400',
                    ];
                @endphp
                <span class="inline-block mt-1 px-2.5 py-0.5 text-xs font-medium rounded-full {{ $statusColors[$ticket->status] ?? '' }}">
                    {{ ucfirst($ticket->status) }}
                </span>
            </div>
            <div>
                <p class="text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase">Raised On</p>
                <p class="text-sm text-neutral-900 dark:text-white mt-1">{{ $ticket->created_at->format('d M Y, h:i A') }}</p>
            </div>
        </div>

        <div class="pt-4 border-t border-neutral-100 dark:border-neutral-800">
            <p class="text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase mb-2">Description</p>
            <p class="text-sm text-neutral-700 dark:text-neutral-300 whitespace-pre-wrap">{{ $ticket->description }}</p>
        </div>

        {{-- Status Dates --}}
        @if ($ticket->status_dates && count($ticket->status_dates) > 0)
            <div class="pt-4 border-t border-neutral-100 dark:border-neutral-800">
                <p class="text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase mb-2">Status Timeline</p>
                <div class="flex flex-wrap gap-3">
                    @foreach ($ticket->status_dates as $status => $date)
                        <div class="text-xs bg-neutral-50 dark:bg-neutral-800 px-3 py-1.5 rounded-lg">
                            <span class="font-medium text-neutral-700 dark:text-neutral-300">{{ ucfirst($status) }}:</span>
                            <span class="text-neutral-500">{{ \Carbon\Carbon::parse($date)->format('d M Y, h:i A') }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    {{-- Update Status --}}
    <div class="bg-white dark:bg-neutral-900 rounded-xl border border-neutral-200 dark:border-neutral-800 p-6">
        <h3 class="text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-3">Update Status</h3>
        <div class="flex items-center gap-3">
            <select wire:model="newStatus" class="px-3 py-2.5 border border-neutral-300 dark:border-neutral-700 rounded-lg text-sm bg-white dark:bg-neutral-800 dark:text-white">
                <option value="raised">Raised</option>
                <option value="in-progress">In Progress</option>
                <option value="resolved">Resolved</option>
                <option value="closed">Closed</option>
            </select>
            <button wire:click="updateStatus" class="px-4 py-2.5 text-sm font-medium text-white bg-[#464d79] rounded-lg hover:bg-[#3a4166] transition-colors">Update</button>
        </div>
    </div>

    {{-- Comments --}}
    <div class="bg-white dark:bg-neutral-900 rounded-xl border border-neutral-200 dark:border-neutral-800 p-6 space-y-4">
        <h3 class="text-sm font-semibold text-neutral-700 dark:text-neutral-300">Comments ({{ count($ticket->comments ?? []) }})</h3>

        {{-- Existing Comments --}}
        @if ($ticket->comments && count($ticket->comments) > 0)
            <div class="space-y-3 max-h-96 overflow-y-auto">
                @foreach ($ticket->comments as $comment)
                    <div class="p-3 rounded-lg {{ ($comment['role'] ?? 'user') === 'admin' ? 'bg-blue-50 dark:bg-blue-900/10 border border-blue-100 dark:border-blue-900/30' : 'bg-neutral-50 dark:bg-neutral-800 border border-neutral-100 dark:border-neutral-700' }}">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-xs font-semibold {{ ($comment['role'] ?? 'user') === 'admin' ? 'text-blue-700 dark:text-blue-400' : 'text-neutral-700 dark:text-neutral-300' }}">
                                {{ $comment['author'] ?? 'Unknown' }}
                                <span class="font-normal text-neutral-400 ml-1">({{ ucfirst($comment['role'] ?? 'user') }})</span>
                            </span>
                            <span class="text-[10px] text-neutral-400">{{ $comment['created_at'] ?? '' }}</span>
                        </div>
                        <p class="text-sm text-neutral-700 dark:text-neutral-300">{{ $comment['message'] ?? '' }}</p>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-sm text-neutral-500">No comments yet.</p>
        @endif

        {{-- Add Comment --}}
        <div class="pt-4 border-t border-neutral-100 dark:border-neutral-800">
            <label class="block text-xs font-medium text-neutral-600 dark:text-neutral-400 mb-1">Add Comment</label>
            <textarea wire:model="newComment" rows="3" class="w-full px-3 py-2.5 border border-neutral-300 dark:border-neutral-700 rounded-lg text-sm bg-white dark:bg-neutral-800 dark:text-white resize-none" placeholder="Type your reply..."></textarea>
            @error('newComment') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            <div class="flex justify-end mt-2">
                <button wire:click="addComment" class="px-4 py-2 text-sm font-medium text-white bg-[#464d79] rounded-lg hover:bg-[#3a4166] transition-colors">Add Comment</button>
            </div>
        </div>
    </div>
</div>
