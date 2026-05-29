<div>
    @if ($employments->count())
        <div class="space-y-3 mb-4">
            @foreach ($employments as $emp)
                <div class="p-4 bg-neutral-50 rounded-lg border border-neutral-100">
                    <div class="flex items-start justify-between">
                        <div>
                            <h4 class="text-sm font-semibold text-neutral-900">{{ $emp->job_title }}</h4>
                            <p class="text-xs text-neutral-600">{{ $emp->company_name }}</p>
                            <div class="flex items-center gap-2 mt-1.5 text-xs text-neutral-400">
                                <span>{{ $emp->getDurationLabel() }}</span>
                                <span class="px-1.5 py-0.5 bg-neutral-100 rounded">{{ str_replace('_', ' ', ucfirst($emp->employment_type)) }}</span>
                                @if ($emp->is_current)<span class="px-1.5 py-0.5 bg-green-100 text-green-700 rounded">Current</span>@endif
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <button wire:click="openForm({{ $emp->id }})" class="text-xs text-[#464d79] hover:underline">Edit</button>
                            <button wire:click="delete({{ $emp->id }})" wire:confirm="Delete?" class="text-xs text-red-500 hover:underline">Delete</button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-sm text-neutral-400 italic mb-4">No work experience added yet.</p>
    @endif

    @if (!$showForm)
        <button wire:click="openForm" class="text-sm font-medium text-[#464d79] hover:underline">+ Add Employment</button>
    @endif

    @if ($showForm)
        <div class="p-4 bg-neutral-50 rounded-lg border border-neutral-200 mt-3">
            <form wire:submit="save" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-neutral-600 mb-1">Company Name *</label>
                        <input type="text" wire:model="company_name" class="w-full px-3 py-2 border border-neutral-300 rounded-lg text-sm">
                        @error('company_name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-neutral-600 mb-1">Job Title *</label>
                        <input type="text" wire:model="job_title" class="w-full px-3 py-2 border border-neutral-300 rounded-lg text-sm">
                        @error('job_title') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-neutral-600 mb-1">Employment Type *</label>
                        <select wire:model="employment_type" class="w-full px-3 py-2 border border-neutral-300 rounded-lg text-sm">
                            <option value="full_time">Full Time</option>
                            <option value="part_time">Part Time</option>
                            <option value="contract">Contract</option>
                            <option value="internship">Internship</option>
                            <option value="freelance">Freelance</option>
                        </select>
                    </div>
                    <div class="flex items-end pb-1">
                        <label class="flex items-center gap-2 text-sm">
                            <input type="checkbox" wire:model.live="is_current" class="rounded border-neutral-300 text-[#464d79]"> Currently Working Here
                        </label>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-neutral-600 mb-1">Joining Date *</label>
                        <input type="date" wire:model="joining_date" class="w-full px-3 py-2 border border-neutral-300 rounded-lg text-sm">
                        @error('joining_date') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    @if (!$is_current)
                        <div>
                            <label class="block text-xs font-medium text-neutral-600 mb-1">Leaving Date *</label>
                            <input type="date" wire:model="leaving_date" class="w-full px-3 py-2 border border-neutral-300 rounded-lg text-sm">
                            @error('leaving_date') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    @endif
                    <div>
                        <label class="block text-xs font-medium text-neutral-600 mb-1">Current Salary</label>
                        <div class="flex gap-2">
                            <select wire:model="salary_currency" class="px-3 py-2 border border-neutral-300 rounded-lg text-sm w-20">
                                <option value="INR">INR</option>
                                <option value="USD">USD</option>
                                <option value="GBP">GBP</option>
                                <option value="EUR">EUR</option>
                            </select>
                            <input type="number" wire:model="current_salary" class="flex-1 px-3 py-2 border border-neutral-300 rounded-lg text-sm" placeholder="Annual salary">
                        </div>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-medium text-neutral-600 mb-1">Responsibilities / Description</label>
                    <textarea wire:model="responsibilities" rows="3" maxlength="1000" class="w-full px-3 py-2 border border-neutral-300 rounded-lg text-sm resize-none"></textarea>
                </div>
                <div class="flex items-center gap-3">
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-[#464d79] rounded-lg hover:bg-[#3a4166]">{{ $editingId ? 'Update' : 'Save' }}</button>
                    <button type="button" wire:click="cancel" class="px-4 py-2 text-sm font-medium text-neutral-600 hover:text-neutral-800">Cancel</button>
                </div>
            </form>
        </div>
    @endif
</div>
