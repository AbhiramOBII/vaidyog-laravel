<div>
    @include('livewire.job-seeker.profile.recognitions._recognition-list')
    @if (!$showForm)
        <button wire:click="openForm" class="text-xs font-medium text-[#464d79] hover:underline">+ Add Honour / Award</button>
    @else
        <div class="p-3 bg-white rounded-lg border border-neutral-200 mt-2">
            <form wire:submit="save" class="space-y-3">
                <input type="text" wire:model="title" placeholder="Award Title *" class="w-full px-3 py-2 border border-neutral-300 rounded-lg text-sm">
                @error('title') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                <div class="grid grid-cols-2 gap-3">
                    <input type="text" wire:model="issuing_body" placeholder="Issuing Body" class="px-3 py-2 border border-neutral-300 rounded-lg text-sm">
                    <input type="date" wire:model="award_date" class="px-3 py-2 border border-neutral-300 rounded-lg text-sm">
                </div>
                <textarea wire:model="description" rows="2" placeholder="Description" class="w-full px-3 py-2 border border-neutral-300 rounded-lg text-sm resize-none"></textarea>
                <div class="flex gap-2">
                    <button type="submit" class="px-3 py-1.5 text-xs font-medium text-white bg-[#464d79] rounded-lg">{{ $editingId ? 'Update' : 'Save' }}</button>
                    <button type="button" wire:click="cancel" class="px-3 py-1.5 text-xs text-neutral-600">Cancel</button>
                </div>
            </form>
        </div>
    @endif
</div>
