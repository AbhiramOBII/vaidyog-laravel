<div>
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">Featured Job Plans</h1>
        <button wire:click="create" class="h-9 px-4 inline-flex items-center gap-1.5 rounded-lg text-sm font-medium bg-[#464d79] text-white hover:bg-[#464d79]/90 transition-colors">+ New Plan</button>
    </div>

    <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-neutral-50 dark:bg-neutral-900 text-left">
                <tr>
                    <th class="px-4 py-3 font-medium text-neutral-500">Recruiter Type</th>
                    <th class="px-4 py-3 font-medium text-neutral-500">Name</th>
                    <th class="px-4 py-3 font-medium text-neutral-500">Price/Post</th>
                    <th class="px-4 py-3 font-medium text-neutral-500">Duration (days)</th>
                    <th class="px-4 py-3 font-medium text-neutral-500">Active</th>
                    <th class="px-4 py-3 font-medium text-neutral-500">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-100 dark:divide-neutral-700">
                @forelse($plans as $plan)
                <tr>
                    <td class="px-4 py-3">{{ $plan->recruiter_type?->label() ?? 'All' }}</td>
                    <td class="px-4 py-3 font-medium">{{ $plan->name }}</td>
                    <td class="px-4 py-3">₹{{ number_format($plan->price_per_post) }}</td>
                    <td class="px-4 py-3">{{ $plan->featured_duration_days }}</td>
                    <td class="px-4 py-3"><span class="w-2 h-2 rounded-full inline-block {{ $plan->is_active ? 'bg-green-500' : 'bg-neutral-300' }}"></span></td>
                    <td class="px-4 py-3 flex gap-2">
                        <button wire:click="edit({{ $plan->id }})" class="text-xs text-[#464d79] hover:underline">Edit</button>
                        <button wire:click="delete({{ $plan->id }})" wire:confirm="Delete this plan?" class="text-xs text-red-500 hover:underline">Delete</button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-4 py-6 text-center text-neutral-500">No featured plans yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Modal --}}
    @if($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/50" wire:click="$set('showModal', false)"></div>
        <div class="relative w-full max-w-md bg-white dark:bg-neutral-800 rounded-2xl shadow-xl p-6 space-y-4">
            <h3 class="text-lg font-semibold">{{ $editingId ? 'Edit' : 'New' }} Featured Plan</h3>
            <div>
                <label class="block text-sm font-medium mb-1">Recruiter Type (optional)</label>
                <select wire:model="recruiter_type" class="w-full h-10 px-3 border rounded-lg text-sm">
                    <option value="">All Types</option>
                    @foreach($medTypes as $type)
                    <option value="{{ $type->value }}">{{ $type->label() }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Name</label>
                <input type="text" wire:model="name" class="w-full h-10 px-3 border rounded-lg text-sm" />
                @error('name') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Price per Post (₹)</label>
                    <input type="number" step="0.01" wire:model="price_per_post" class="w-full h-10 px-3 border rounded-lg text-sm" />
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Duration (days)</label>
                    <input type="number" wire:model="featured_duration_days" class="w-full h-10 px-3 border rounded-lg text-sm" />
                </div>
            </div>
            <label class="flex items-center gap-2 text-sm"><input type="checkbox" wire:model="is_active" class="rounded"> Active</label>
            <div class="flex gap-3 pt-2">
                <button wire:click="save" class="h-10 px-5 bg-[#464d79] text-white rounded-lg text-sm font-medium">Save</button>
                <button wire:click="$set('showModal', false)" class="h-10 px-5 bg-neutral-100 text-neutral-700 rounded-lg text-sm font-medium">Cancel</button>
            </div>
        </div>
    </div>
    @endif
</div>
