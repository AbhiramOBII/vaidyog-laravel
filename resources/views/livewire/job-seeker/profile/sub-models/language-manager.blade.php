<div>
    {{-- List --}}
    @if ($languages->count())
        <div class="space-y-2 mb-4">
            @foreach ($languages as $lang)
                <div class="flex items-center justify-between p-3 bg-neutral-50 rounded-lg">
                    <div class="flex items-center gap-3">
                        <span class="text-sm font-medium text-neutral-900">{{ $lang->name }}</span>
                        <span class="px-2 py-0.5 text-xs font-medium rounded-full {{ $lang->proficiency === 'native' ? 'bg-green-100 text-green-700' : ($lang->proficiency === 'fluent' ? 'bg-blue-100 text-blue-700' : ($lang->proficiency === 'intermediate' ? 'bg-amber-100 text-amber-700' : 'bg-neutral-100 text-neutral-600')) }}">{{ ucfirst($lang->proficiency) }}</span>
                        <div class="flex items-center gap-1.5 text-xs text-neutral-500">
                            @if ($lang->can_read)<span class="px-1.5 py-0.5 bg-white border rounded">R</span>@endif
                            @if ($lang->can_write)<span class="px-1.5 py-0.5 bg-white border rounded">W</span>@endif
                            @if ($lang->can_speak)<span class="px-1.5 py-0.5 bg-white border rounded">S</span>@endif
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <button wire:click="openForm({{ $lang->id }})" class="text-xs text-[#464d79] hover:underline">Edit</button>
                        <button wire:click="delete({{ $lang->id }})" wire:confirm="Delete this language?" class="text-xs text-red-500 hover:underline">Delete</button>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-sm text-neutral-400 italic mb-4">No languages added yet.</p>
    @endif

    {{-- Add button --}}
    @if (!$showForm)
        <button wire:click="openForm" class="text-sm font-medium text-[#464d79] hover:underline">+ Add Language</button>
    @endif

    {{-- Form --}}
    @if ($showForm)
        <div class="p-4 bg-neutral-50 rounded-lg border border-neutral-200 mt-3">
            <form wire:submit="save" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-neutral-600 mb-1">Language</label>
                        <input type="text" wire:model="name" class="w-full px-3 py-2 border border-neutral-300 rounded-lg text-sm" placeholder="e.g. English, Hindi">
                        @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-neutral-600 mb-1">Proficiency</label>
                        <select wire:model="proficiency" class="w-full px-3 py-2 border border-neutral-300 rounded-lg text-sm">
                            <option value="beginner">Beginner</option>
                            <option value="intermediate">Intermediate</option>
                            <option value="fluent">Fluent</option>
                            <option value="native">Native</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-medium text-neutral-600 mb-2">Abilities</label>
                    <div class="flex items-center gap-4">
                        <label class="flex items-center gap-2 text-sm"><input type="checkbox" wire:model="can_read" class="rounded border-neutral-300 text-[#464d79]"> Read</label>
                        <label class="flex items-center gap-2 text-sm"><input type="checkbox" wire:model="can_write" class="rounded border-neutral-300 text-[#464d79]"> Write</label>
                        <label class="flex items-center gap-2 text-sm"><input type="checkbox" wire:model="can_speak" class="rounded border-neutral-300 text-[#464d79]"> Speak</label>
                    </div>
                    @error('can_read') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="flex items-center gap-3">
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-[#464d79] rounded-lg hover:bg-[#3a4166]">{{ $editingId ? 'Update' : 'Save' }}</button>
                    <button type="button" wire:click="cancel" class="px-4 py-2 text-sm font-medium text-neutral-600 hover:text-neutral-800">Cancel</button>
                </div>
            </form>
        </div>
    @endif
</div>
