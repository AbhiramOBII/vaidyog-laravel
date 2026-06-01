<div class="max-w-2xl mx-auto space-y-6">
    <h2 class="text-xl font-bold text-neutral-900 dark:text-white">Support</h2>

    @if (session('success'))
        <div class="p-3 text-sm text-green-700 bg-green-50 border border-green-200 rounded-lg">{{ session('success') }}</div>
    @endif

    {{-- Viewing a Ticket --}}
    @if ($viewingTicket)
        <div class="bg-white dark:bg-neutral-900 rounded-xl border border-neutral-200 dark:border-neutral-800 p-6 space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-base font-semibold text-neutral-900 dark:text-white">#{{ $viewingTicket->id }} — {{ $viewingTicket->title }}</h3>
                    <p class="text-xs text-neutral-500 mt-0.5">Raised on {{ $viewingTicket->created_at->format('d M Y, h:i A') }}</p>
                </div>
                <button wire:click="closeTicketView" class="text-xs font-medium text-neutral-500 hover:text-neutral-700 dark:hover:text-neutral-300">&larr; Back</button>
            </div>

            @php
                $statusColors = [
                    'raised' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
                    'in-progress' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                    'resolved' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                    'closed' => 'bg-neutral-100 text-neutral-600 dark:bg-neutral-700 dark:text-neutral-400',
                ];
            @endphp
            <span class="inline-block px-2.5 py-0.5 text-xs font-medium rounded-full {{ $statusColors[$viewingTicket->status] ?? '' }}">
                {{ ucfirst($viewingTicket->status) }}
            </span>

            <div class="pt-3 border-t border-neutral-100 dark:border-neutral-800">
                <p class="text-xs font-medium text-neutral-500 uppercase mb-1">Description</p>
                <p class="text-sm text-neutral-700 dark:text-neutral-300 whitespace-pre-wrap">{{ $viewingTicket->description }}</p>
            </div>

            {{-- Conversation --}}
            @if ($viewingTicket->comments && count($viewingTicket->comments) > 0)
                <div class="pt-3 border-t border-neutral-100 dark:border-neutral-800 space-y-3">
                    <p class="text-xs font-medium text-neutral-500 uppercase">Conversation</p>
                    @foreach ($viewingTicket->comments as $comment)
                        <div class="p-3 rounded-lg {{ ($comment['role'] ?? 'user') === 'admin' ? 'bg-blue-50 dark:bg-blue-900/10 border border-blue-100 dark:border-blue-900/30' : 'bg-neutral-50 dark:bg-neutral-800 border border-neutral-100 dark:border-neutral-700' }}">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-xs font-semibold {{ ($comment['role'] ?? 'user') === 'admin' ? 'text-blue-700 dark:text-blue-400' : 'text-neutral-700 dark:text-neutral-300' }}">
                                    {{ $comment['author'] ?? 'Unknown' }}
                                    <span class="font-normal text-neutral-400 ml-1">({{ ($comment['role'] ?? 'user') === 'admin' ? 'Support' : 'You' }})</span>
                                </span>
                                <span class="text-[10px] text-neutral-400">{{ $comment['created_at'] ?? '' }}</span>
                            </div>
                            <p class="text-sm text-neutral-700 dark:text-neutral-300">{{ $comment['message'] ?? '' }}</p>
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- Reply --}}
            @if (!in_array($viewingTicket->status, ['closed', 'resolved']))
                <div class="pt-3 border-t border-neutral-100 dark:border-neutral-800">
                    <label class="block text-xs font-medium text-neutral-600 dark:text-neutral-400 mb-1">Your Reply</label>
                    <textarea wire:model="replyMessage" rows="3" maxlength="2000" class="w-full px-3 py-2.5 border border-neutral-300 dark:border-neutral-700 rounded-lg text-sm bg-white dark:bg-neutral-800 dark:text-white resize-none" placeholder="Type your reply..."></textarea>
                    @error('replyMessage') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    <div class="flex justify-end mt-2">
                        <button wire:click="addReply" class="px-4 py-2 text-sm font-medium text-white bg-[#464d79] rounded-lg hover:bg-[#3a4166] transition-colors">Send Reply</button>
                    </div>
                </div>
            @else
                <p class="text-xs text-neutral-500 italic pt-3 border-t border-neutral-100 dark:border-neutral-800">This ticket is {{ $viewingTicket->status }}. No further replies allowed.</p>
            @endif
        </div>
    @else
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
                    <h3 class="text-sm font-semibold text-neutral-700 dark:text-neutral-300">My Tickets</h3>
                </div>
                <div class="divide-y divide-neutral-100 dark:divide-neutral-800">
                    @foreach ($tickets as $ticket)
                        <button wire:click="viewTicket({{ $ticket->id }})" class="w-full px-6 py-4 text-left hover:bg-neutral-50 dark:hover:bg-neutral-800/30 transition-colors">
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
                                @php $lastComment = collect($ticket->comments)->last(); @endphp
                                <div class="mt-2 p-2 bg-neutral-50 dark:bg-neutral-800 rounded text-xs text-neutral-600 dark:text-neutral-400">
                                    Latest: {{ Str::limit($lastComment['message'] ?? '', 80) }}
                                </div>
                            @endif
                        </button>
                    @endforeach
                </div>
            </div>
        @endif
    @endif
</div>
