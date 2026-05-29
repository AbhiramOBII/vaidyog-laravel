<div>
    @if ($projects->count())
        <div class="space-y-3 mb-4">
            @foreach ($projects as $proj)
                <div class="p-4 bg-neutral-50 rounded-lg border border-neutral-100">
                    <div class="flex items-start justify-between">
                        <div>
                            <h4 class="text-sm font-semibold text-neutral-900">{{ $proj->title }}</h4>
                            @if ($proj->client_name)<p class="text-xs text-neutral-600">Client: {{ $proj->client_name }}</p>@endif
                            <div class="flex items-center gap-2 mt-1.5 text-xs text-neutral-400">
                                @if ($proj->start_date)<span>{{ $proj->start_date->format('M Y') }}{{ $proj->end_date ? ' – '.$proj->end_date->format('M Y') : ' – Present' }}</span>@endif
                                <span class="px-1.5 py-0.5 rounded {{ $proj->status === 'ongoing' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' }}">{{ ucfirst($proj->status) }}</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <button wire:click="openForm({{ $proj->id }})" class="text-xs text-[#464d79] hover:underline">Edit</button>
                            <button wire:click="delete({{ $proj->id }})" wire:confirm="Delete?" class="text-xs text-red-500 hover:underline">Delete</button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-sm text-neutral-400 italic mb-4">No projects added yet.</p>
    @endif

    @if (!$showForm)
        <button wire:click="openForm" class="text-sm font-medium text-[#464d79] hover:underline">+ Add Project</button>
    @endif

    @if ($showForm)
        <div class="p-4 bg-neutral-50 rounded-lg border border-neutral-200 mt-3">
            <form wire:submit="save" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-medium text-neutral-600 mb-1">Project Title *</label>
                        <input type="text" wire:model="title" class="w-full px-3 py-2 border border-neutral-300 rounded-lg text-sm">
                        @error('title') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-neutral-600 mb-1">Client Name</label>
                        <input type="text" wire:model="client_name" class="w-full px-3 py-2 border border-neutral-300 rounded-lg text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-neutral-600 mb-1">Location</label>
                        <input type="text" wire:model="location" class="w-full px-3 py-2 border border-neutral-300 rounded-lg text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-neutral-600 mb-1">Status</label>
                        <select wire:model="status" class="w-full px-3 py-2 border border-neutral-300 rounded-lg text-sm">
                            <option value="ongoing">Ongoing</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-xs font-medium text-neutral-600 mb-1">Start Date</label>
                            <input type="date" wire:model="start_date" class="w-full px-3 py-2 border border-neutral-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-neutral-600 mb-1">End Date</label>
                            <input type="date" wire:model="end_date" class="w-full px-3 py-2 border border-neutral-300 rounded-lg text-sm">
                            @error('end_date') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-medium text-neutral-600 mb-1">Details</label>
                    <textarea wire:model="details" rows="3" maxlength="1000" class="w-full px-3 py-2 border border-neutral-300 rounded-lg text-sm resize-none"></textarea>
                </div>
                <div class="flex items-center gap-3">
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-[#464d79] rounded-lg hover:bg-[#3a4166]">{{ $editingId ? 'Update' : 'Save' }}</button>
                    <button type="button" wire:click="cancel" class="px-4 py-2 text-sm font-medium text-neutral-600 hover:text-neutral-800">Cancel</button>
                </div>
            </form>
        </div>
    @endif
</div>
