<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">Feedbacks</h1>
    </div>

    {{-- Filters --}}
    <div class="flex flex-wrap gap-3">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search by name or feedback..." class="px-4 py-2 border border-neutral-300 dark:border-neutral-700 rounded-lg text-sm bg-white dark:bg-neutral-800 dark:text-white w-64">
        <select wire:model.live="userType" class="px-3 py-2 border border-neutral-300 dark:border-neutral-700 rounded-lg text-sm bg-white dark:bg-neutral-800 dark:text-white">
            <option value="">All Users</option>
            <option value="job_seeker">Job Seekers</option>
            <option value="recruiter">Recruiters</option>
        </select>
        <select wire:model.live="readStatus" class="px-3 py-2 border border-neutral-300 dark:border-neutral-700 rounded-lg text-sm bg-white dark:bg-neutral-800 dark:text-white">
            <option value="">All</option>
            <option value="unread">Unread</option>
            <option value="read">Read</option>
        </select>
    </div>

    {{-- Table --}}
    <div class="bg-white dark:bg-neutral-900 rounded-xl border border-neutral-200 dark:border-neutral-800 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-neutral-50 dark:bg-neutral-800/50">
                <tr>
                    <th class="px-4 py-3 text-left font-medium text-neutral-600 dark:text-neutral-300">Name</th>
                    <th class="px-4 py-3 text-left font-medium text-neutral-600 dark:text-neutral-300">Feedback</th>
                    <th class="px-4 py-3 text-left font-medium text-neutral-600 dark:text-neutral-300">Type</th>
                    <th class="px-4 py-3 text-left font-medium text-neutral-600 dark:text-neutral-300">Status</th>
                    <th class="px-4 py-3 text-left font-medium text-neutral-600 dark:text-neutral-300">Date</th>
                    <th class="px-4 py-3 text-right font-medium text-neutral-600 dark:text-neutral-300">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-100 dark:divide-neutral-800">
                @forelse ($feedbacks as $fb)
                    <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-800/30 {{ !$fb->read_status ? 'bg-blue-50/50 dark:bg-blue-900/10' : '' }}">
                        <td class="px-4 py-3">
                            <div>
                                <p class="font-medium text-neutral-900 dark:text-white">{{ $fb->name }}</p>
                                <p class="text-xs text-neutral-500">{{ $fb->user?->email ?? '—' }}</p>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-neutral-700 dark:text-neutral-300 max-w-xs">
                            <p class="line-clamp-2">{{ $fb->feedback }}</p>
                        </td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-0.5 text-xs font-medium rounded-full {{ $fb->user_type === 'recruiter' ? 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400' : 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' }}">
                                {{ $fb->user_type === 'recruiter' ? 'Recruiter' : 'Job Seeker' }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            @if ($fb->read_status)
                                <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">Read</span>
                            @else
                                <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400">Unread</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-neutral-500 dark:text-neutral-400 text-xs">{{ $fb->created_at->format('d M Y, h:i A') }}</td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex items-center justify-end gap-2">
                                @if (!$fb->read_status)
                                    <button wire:click="markAsRead({{ $fb->id }})" class="px-2 py-1 text-xs font-medium text-green-600 hover:bg-green-50 dark:hover:bg-green-900/20 rounded transition-colors">Mark Read</button>
                                @else
                                    <button wire:click="markAsUnread({{ $fb->id }})" class="px-2 py-1 text-xs font-medium text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-900/20 rounded transition-colors">Unread</button>
                                @endif
                                @if(auth('admin')->user()->hasPermission('feedbacks.delete'))
                                <button wire:click="delete({{ $fb->id }})" wire:confirm="Delete this feedback?" class="px-2 py-1 text-xs font-medium text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded transition-colors">Delete</button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-neutral-500">No feedback found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $feedbacks->links() }}</div>
</div>
