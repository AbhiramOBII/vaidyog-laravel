<div>
    @if ($educations->count())
        <div class="space-y-3 mb-4">
            @foreach ($educations as $edu)
                <div class="p-4 bg-neutral-50 rounded-lg border border-neutral-100">
                    <div class="flex items-start justify-between">
                        <div>
                            <h4 class="text-sm font-semibold text-neutral-900">{{ $edu->degree }}</h4>
                            <p class="text-xs text-neutral-600">{{ $edu->university }}</p>
                            @if ($edu->specialization)<p class="text-xs text-neutral-500">{{ $edu->specialization }}</p>@endif
                            <div class="flex items-center gap-2 mt-1.5 text-xs text-neutral-400">
                                <span>{{ $edu->getDurationLabel() }}</span>
                                @if ($edu->grade)<span>&middot; {{ $edu->getGradeLabel() }}</span>@endif
                                <span class="px-1.5 py-0.5 bg-neutral-100 rounded">{{ str_replace('_', ' ', ucfirst($edu->course_type)) }}</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <button wire:click="openForm({{ $edu->id }})" class="text-xs text-[#464d79] hover:underline">Edit</button>
                            <button wire:click="delete({{ $edu->id }})" wire:confirm="Delete?" class="text-xs text-red-500 hover:underline">Delete</button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-sm text-neutral-400 italic mb-4">No education added yet.</p>
    @endif

    @if (!$showForm)
        <button wire:click="openForm" class="text-sm font-medium text-[#464d79] hover:underline">+ Add Education</button>
    @endif

    @if ($showForm)
        <div class="p-4 bg-neutral-50 rounded-lg border border-neutral-200 mt-3">
            <form wire:submit="save" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-neutral-600 mb-1">Degree *</label>
                        <input type="text" wire:model="degree" class="w-full px-3 py-2 border border-neutral-300 rounded-lg text-sm" placeholder="e.g. MBBS, MD, BDS">
                        @error('degree') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-neutral-600 mb-1">University *</label>
                        <input type="text" wire:model="university" class="w-full px-3 py-2 border border-neutral-300 rounded-lg text-sm">
                        @error('university') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-neutral-600 mb-1">Course</label>
                        <input type="text" wire:model="course" class="w-full px-3 py-2 border border-neutral-300 rounded-lg text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-neutral-600 mb-1">Specialization</label>
                        <input type="text" wire:model="specialization" class="w-full px-3 py-2 border border-neutral-300 rounded-lg text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-neutral-600 mb-1">Course Type *</label>
                        <select wire:model="course_type" class="w-full px-3 py-2 border border-neutral-300 rounded-lg text-sm">
                            <option value="full_time">Full Time</option>
                            <option value="part_time">Part Time</option>
                            <option value="distance_learning">Distance Learning</option>
                            <option value="online">Online</option>
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-xs font-medium text-neutral-600 mb-1">Start Year *</label>
                            <input type="number" wire:model="course_duration_start" min="1950" max="{{ date('Y') }}" class="w-full px-3 py-2 border border-neutral-300 rounded-lg text-sm">
                            @error('course_duration_start') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-neutral-600 mb-1">End Year</label>
                            <input type="number" wire:model="course_duration_end" min="1950" max="{{ date('Y') + 6 }}" class="w-full px-3 py-2 border border-neutral-300 rounded-lg text-sm" placeholder="Pursuing">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-neutral-600 mb-1">Grading System</label>
                        <select wire:model="grading_system" class="w-full px-3 py-2 border border-neutral-300 rounded-lg text-sm">
                            <option value="percentage">Percentage</option>
                            <option value="cgpa">CGPA</option>
                            <option value="grade">Grade</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-neutral-600 mb-1">Grade / Score</label>
                        <input type="text" wire:model="grade" class="w-full px-3 py-2 border border-neutral-300 rounded-lg text-sm" placeholder="e.g. 85 or 8.5">
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-[#464d79] rounded-lg hover:bg-[#3a4166]">{{ $editingId ? 'Update' : 'Save' }}</button>
                    <button type="button" wire:click="cancel" class="px-4 py-2 text-sm font-medium text-neutral-600 hover:text-neutral-800">Cancel</button>
                </div>
            </form>
        </div>
    @endif
</div>
