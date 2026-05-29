<div>
    @include('livewire.job-seeker.profile.recognitions._recognition-list')
    @if (!$showForm)
        <button wire:click="openForm" class="text-xs font-medium text-[#464d79] hover:underline">+ Add Affiliation</button>
    @else
        <div class="p-3 bg-white rounded-lg border border-neutral-200 mt-2">
            <form wire:submit="save" class="space-y-3">
                <input type="text" wire:model="organization_name" placeholder="Organization Name *" class="w-full px-3 py-2 border border-neutral-300 rounded-lg text-sm">
                @error('organization_name') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                <input type="text" wire:model="role" placeholder="Role / Position" class="w-full px-3 py-2 border border-neutral-300 rounded-lg text-sm">
                <div class="grid grid-cols-2 gap-3">
                    <input type="date" wire:model="member_since" placeholder="Member Since" class="px-3 py-2 border border-neutral-300 rounded-lg text-sm">
                    @if (!$is_current)
                        <input type="date" wire:model="member_until" placeholder="Until" class="px-3 py-2 border border-neutral-300 rounded-lg text-sm">
                    @endif
                </div>
                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" wire:model.live="is_current" class="rounded border-neutral-300 text-[#464d79]"> Current Member
                </label>
                <div class="flex gap-2">
                    <button type="submit" class="px-3 py-1.5 text-xs font-medium text-white bg-[#464d79] rounded-lg">{{ $editingId ? 'Update' : 'Save' }}</button>
                    <button type="button" wire:click="cancel" class="px-3 py-1.5 text-xs text-neutral-600">Cancel</button>
                </div>
            </form>
        </div>
    @endif
</div>
