<div>
    @if ($certifications->count())
        <div class="space-y-3 mb-4">
            @foreach ($certifications as $cert)
                <div class="p-4 bg-neutral-50 rounded-lg border border-neutral-100">
                    <div class="flex items-start justify-between">
                        <div>
                            <h4 class="text-sm font-semibold text-neutral-900">{{ $cert->name }}</h4>
                            @if ($cert->medical_institute)<p class="text-xs text-neutral-500">{{ $cert->medical_institute }}</p>@endif
                            <div class="flex items-center gap-2 mt-1.5">
                                @if ($cert->certification_id)<span class="text-xs text-neutral-400">ID: {{ $cert->certification_id }}</span>@endif
                                @php $status = $cert->getValidityStatus(); @endphp
                                <span class="px-2 py-0.5 text-xs font-medium rounded-full {{ $status === 'no_expiry' ? 'bg-blue-100 text-blue-700' : ($status === 'valid' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700') }}">
                                    {{ $status === 'no_expiry' ? 'No Expiry' : ($status === 'valid' ? 'Valid until '.$cert->validity_end->format('M Y') : 'Expired '.$cert->validity_end->format('M Y')) }}
                                </span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            @if ($cert->certification_url)<a href="{{ $cert->certification_url }}" target="_blank" class="text-xs text-[#464d79]">View</a>@endif
                            <button wire:click="openForm({{ $cert->id }})" class="text-xs text-[#464d79] hover:underline">Edit</button>
                            <button wire:click="delete({{ $cert->id }})" wire:confirm="Delete?" class="text-xs text-red-500 hover:underline">Delete</button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-sm text-neutral-400 italic mb-4">No certifications added yet.</p>
    @endif

    @if (!$showForm)
        <button wire:click="openForm" class="text-sm font-medium text-[#464d79] hover:underline">+ Add Certification</button>
    @endif

    @if ($showForm)
        <div class="p-4 bg-neutral-50 rounded-lg border border-neutral-200 mt-3">
            <form wire:submit="save" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-medium text-neutral-600 mb-1">Certification Name *</label>
                        <input type="text" wire:model="name" class="w-full px-3 py-2 border border-neutral-300 rounded-lg text-sm">
                        @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-neutral-600 mb-1">Issuing Institution</label>
                        <input type="text" wire:model="medical_institute" class="w-full px-3 py-2 border border-neutral-300 rounded-lg text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-neutral-600 mb-1">Completion Date</label>
                        <input type="date" wire:model="completion_date" class="w-full px-3 py-2 border border-neutral-300 rounded-lg text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-neutral-600 mb-1">Certificate ID</label>
                        <input type="text" wire:model="certification_id" class="w-full px-3 py-2 border border-neutral-300 rounded-lg text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-neutral-600 mb-1">Certificate URL</label>
                        <input type="url" wire:model="certification_url" class="w-full px-3 py-2 border border-neutral-300 rounded-lg text-sm">
                        @error('certification_url') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div>
                    <label class="flex items-center gap-2 text-sm">
                        <input type="checkbox" wire:model.live="no_expiry" class="rounded border-neutral-300 text-[#464d79]"> No Expiry Date
                    </label>
                </div>
                @if (!$no_expiry)
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-neutral-600 mb-1">Validity Start</label>
                            <input type="date" wire:model="validity_start" class="w-full px-3 py-2 border border-neutral-300 rounded-lg text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-neutral-600 mb-1">Validity End</label>
                            <input type="date" wire:model="validity_end" class="w-full px-3 py-2 border border-neutral-300 rounded-lg text-sm">
                            @error('validity_end') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                @endif
                <div class="flex items-center gap-3">
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-[#464d79] rounded-lg hover:bg-[#3a4166]">{{ $editingId ? 'Update' : 'Save' }}</button>
                    <button type="button" wire:click="cancel" class="px-4 py-2 text-sm font-medium text-neutral-600 hover:text-neutral-800">Cancel</button>
                </div>
            </form>
        </div>
    @endif
</div>
