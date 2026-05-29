<div class="max-w-3xl">
    <h1 class="text-2xl font-bold text-neutral-900 dark:text-white mb-6">{{ $plan ? 'Edit' : 'Create' }} Job Seeker Plan</h1>

    <form wire:submit="save" class="space-y-6">
        {{-- Plan basics --}}
        <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 space-y-4">
            <h2 class="text-sm font-semibold text-neutral-500 uppercase">Plan Details</h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Name</label>
                    <input type="text" wire:model.live="name" class="w-full h-10 px-3 border rounded-lg text-sm" />
                    @error('name') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Slug</label>
                    <input type="text" wire:model="slug" class="w-full h-10 px-3 border rounded-lg text-sm" />
                    @error('slug') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Ranking</label>
                    <select wire:model="ranking" class="w-full h-10 px-3 border rounded-lg text-sm">
                        <option value="A">A — Platinum</option>
                        <option value="B">B — Gold</option>
                        <option value="C">C — Silver</option>
                        <option value="D">D — Basic</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Sort Order</label>
                    <input type="number" wire:model="sort_order" class="w-full h-10 px-3 border rounded-lg text-sm" />
                </div>
                <div class="flex items-end gap-4">
                    <label class="flex items-center gap-2 text-sm"><input type="checkbox" wire:model="is_active" class="rounded"> Active</label>
                    <label class="flex items-center gap-2 text-sm"><input type="checkbox" wire:model="is_recommended" class="rounded"> Recommended</label>
                </div>
            </div>

            {{-- Description repeater --}}
            <div>
                <label class="block text-sm font-medium mb-1">Feature Descriptions</label>
                @foreach($description as $i => $desc)
                <div class="flex gap-2 mb-2">
                    <input type="text" wire:model="description.{{ $i }}" class="flex-1 h-9 px-3 border rounded-lg text-sm" placeholder="Feature text" />
                    <button type="button" wire:click="removeDescription({{ $i }})" class="text-red-500 text-xs hover:underline">Remove</button>
                </div>
                @endforeach
                <button type="button" wire:click="addDescription" class="text-xs text-[#464d79] font-medium hover:underline">+ Add feature</button>
            </div>
        </div>

        {{-- Options --}}
        <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 space-y-4">
            <h2 class="text-sm font-semibold text-neutral-500 uppercase">Subscription Options</h2>
            @error('options') <span class="text-xs text-red-500">{{ $message }}</span> @enderror

            @foreach($options as $i => $opt)
            <div class="border border-neutral-200 dark:border-neutral-700 rounded-lg p-4 space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium">Option {{ $i + 1 }}</span>
                    @if(count($options) > 1)
                    <button type="button" wire:click="removeOption({{ $i }})" class="text-xs text-red-500 hover:underline">Delete</button>
                    @endif
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    <div>
                        <label class="text-xs text-neutral-500">Label</label>
                        <input type="text" wire:model="options.{{ $i }}.label" class="w-full h-9 px-3 border rounded-lg text-sm" placeholder="Monthly" />
                    </div>
                    <div>
                        <label class="text-xs text-neutral-500">Duration Type</label>
                        <select wire:model="options.{{ $i }}.duration_type" class="w-full h-9 px-3 border rounded-lg text-sm">
                            <option value="monthly">Monthly</option>
                            <option value="yearly">Yearly</option>
                            <option value="lifetime">Lifetime</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-xs text-neutral-500">Duration Value</label>
                        <input type="number" wire:model="options.{{ $i }}.duration_value" class="w-full h-9 px-3 border rounded-lg text-sm" min="1" />
                    </div>
                    <div>
                        <label class="text-xs text-neutral-500">Price (INR)</label>
                        <input type="number" step="0.01" wire:model="options.{{ $i }}.price" class="w-full h-9 px-3 border rounded-lg text-sm" min="0" />
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <label class="flex items-center gap-2 text-sm"><input type="checkbox" wire:model="options.{{ $i }}.is_unlimited" class="rounded"> Unlimited</label>
                    @if(!($options[$i]['is_unlimited'] ?? false))
                    <div>
                        <label class="text-xs text-neutral-500">Apps/month</label>
                        <input type="number" wire:model="options.{{ $i }}.applications_per_month" class="w-20 h-8 px-2 border rounded text-sm" min="1" />
                    </div>
                    @endif
                    <label class="flex items-center gap-2 text-sm"><input type="checkbox" wire:model="options.{{ $i }}.is_active" class="rounded"> Active</label>
                </div>
            </div>
            @endforeach

            <button type="button" wire:click="addOption" class="text-sm text-[#464d79] font-medium hover:underline">+ Add Option</button>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="h-10 px-6 bg-[#464d79] text-white rounded-lg text-sm font-medium hover:bg-[#464d79]/90">Save Plan</button>
            <a href="{{ route('admin.plans.jobseeker.index') }}" wire:navigate class="h-10 px-6 inline-flex items-center rounded-lg text-sm font-medium bg-neutral-100 text-neutral-700 hover:bg-neutral-200">Cancel</a>
        </div>
    </form>
</div>
