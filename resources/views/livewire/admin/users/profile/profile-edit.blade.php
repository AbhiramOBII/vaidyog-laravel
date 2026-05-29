<div class="max-w-4xl mx-auto space-y-6" x-data="{ saved: false }" @saved.window="saved = true; setTimeout(() => saved = false, 3000)">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-neutral-900">Edit User Profile</h1>
            <p class="text-sm text-neutral-500 mt-1">Admin editing user #{{ $userId }}</p>
        </div>
        <div class="flex items-center gap-3">
            <span x-show="saved" x-transition.opacity class="text-sm font-medium text-green-600">Saved &#10003;</span>
            <a href="{{ route('admin.users.profile.show', $userId) }}" class="px-4 py-2 text-sm font-medium text-neutral-700 bg-white border border-neutral-300 rounded-lg hover:bg-neutral-50">View Profile</a>
        </div>
    </div>

    <form wire:submit="save" class="space-y-6">
        {{-- Personal Info --}}
        <div class="bg-white rounded-xl border border-neutral-200 p-6">
            <h2 class="text-lg font-semibold text-neutral-900 mb-4">Personal Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-medium text-neutral-600 mb-1">First Name *</label>
                    <input type="text" wire:model="first_name" class="w-full px-3 py-2.5 border border-neutral-300 rounded-lg text-sm">
                    @error('first_name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-neutral-600 mb-1">Last Name *</label>
                    <input type="text" wire:model="last_name" class="w-full px-3 py-2.5 border border-neutral-300 rounded-lg text-sm">
                    @error('last_name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-neutral-600 mb-1">Date of Birth</label>
                    <input type="date" wire:model="date_of_birth" class="w-full px-3 py-2.5 border border-neutral-300 rounded-lg text-sm">
                </div>
                <div>
                    <label class="block text-xs font-medium text-neutral-600 mb-1">Gender</label>
                    <select wire:model="gender" class="w-full px-3 py-2.5 border border-neutral-300 rounded-lg text-sm">
                        <option value="">Select</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                        <option value="prefer_not_to_say">Prefer not to say</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-neutral-600 mb-1">Phone</label>
                    <input type="text" wire:model="phone" class="w-full px-3 py-2.5 border border-neutral-300 rounded-lg text-sm">
                    @error('phone') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-neutral-600 mb-1">City</label>
                    <input type="text" wire:model="city" class="w-full px-3 py-2.5 border border-neutral-300 rounded-lg text-sm">
                </div>
                <div>
                    <label class="block text-xs font-medium text-neutral-600 mb-1">State</label>
                    <select wire:model="state" class="w-full px-3 py-2.5 border border-neutral-300 rounded-lg text-sm">
                        <option value="">Select State</option>
                        @foreach ($indianStates as $st)
                            <option value="{{ $st }}">{{ $st }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-neutral-600 mb-1">Pincode</label>
                    <input type="text" wire:model="pincode" class="w-full px-3 py-2.5 border border-neutral-300 rounded-lg text-sm">
                    @error('pincode') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-neutral-600 mb-1">Nationality</label>
                    <input type="text" wire:model="nationality" class="w-full px-3 py-2.5 border border-neutral-300 rounded-lg text-sm">
                </div>
            </div>
        </div>

        {{-- Professional Info --}}
        <div class="bg-white rounded-xl border border-neutral-200 p-6">
            <h2 class="text-lg font-semibold text-neutral-900 mb-4">Professional Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-medium text-neutral-600 mb-1">Designation</label>
                    <select wire:model.live="designation" class="w-full px-3 py-2.5 border border-neutral-300 rounded-lg text-sm">
                        <option value="">Select</option>
                        @foreach ($designations as $d)
                            <option value="{{ $d }}">{{ $d }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-neutral-600 mb-1">Sub-designation</label>
                    <select wire:model="subdesignation" class="w-full px-3 py-2.5 border border-neutral-300 rounded-lg text-sm" {{ empty($subDesignations) ? 'disabled' : '' }}>
                        <option value="">Select</option>
                        @foreach ($subDesignations as $sd)
                            <option value="{{ $sd }}">{{ $sd }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Skills --}}
            <div class="mt-5">
                <label class="block text-xs font-medium text-neutral-600 mb-1">Skills</label>
                <div class="flex flex-wrap gap-2 mb-2">
                    @foreach ($skills as $i => $skill)
                        <span class="inline-flex items-center gap-1 px-3 py-1 bg-[#4ab098]/10 text-[#4ab098] text-sm font-medium rounded-full">
                            {{ $skill }}
                            <button type="button" wire:click="removeSkill({{ $i }})" class="ml-1 text-[#4ab098]/60 hover:text-red-500">&times;</button>
                        </span>
                    @endforeach
                </div>
                <div class="flex gap-2">
                    <input type="text" wire:model="skillInput" wire:keydown.enter.prevent="addSkill" class="flex-1 px-3 py-2 border border-neutral-300 rounded-lg text-sm" placeholder="Add skill">
                    <button type="button" wire:click="addSkill" class="px-4 py-2 text-sm font-medium text-[#464d79] border border-[#464d79]/30 rounded-lg hover:bg-[#464d79]/5">Add</button>
                </div>
            </div>

            {{-- About --}}
            <div class="mt-5">
                <label class="block text-xs font-medium text-neutral-600 mb-1">About</label>
                <textarea wire:model="about" rows="4" maxlength="1000" class="w-full px-3 py-2.5 border border-neutral-300 rounded-lg text-sm resize-none"></textarea>
            </div>

            {{-- Open to Work --}}
            <div class="mt-4 flex items-center gap-3">
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" wire:model="is_open_to_work" class="sr-only peer">
                    <div class="w-11 h-6 bg-neutral-200 peer-focus:ring-2 peer-focus:ring-[#4ab098]/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-neutral-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#4ab098]"></div>
                </label>
                <span class="text-sm font-medium text-neutral-700">Open to Work</span>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="px-6 py-2.5 text-sm font-medium text-white bg-[#464d79] rounded-lg hover:bg-[#3a4166] transition-colors">Save All Changes</button>
        </div>
    </form>
</div>
