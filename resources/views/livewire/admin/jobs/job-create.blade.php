<div>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">Post New Job (Admin)</h1>
        <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-1">Create a job posting on behalf of a recruiter.</p>
    </div>

    {{-- Admin-only controls --}}
    <div class="mb-6 space-y-4">
        <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-5">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Recruiter <span class="text-red-500">*</span></label>
                    <select wire:model="recruiterId" class="w-full h-11 px-4 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-600 rounded-xl text-sm focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20 @error('recruiterId') border-red-400 @enderror">
                        <option value="">Select recruiter...</option>
                        @foreach($this->recruiters as $r)
                        <option value="{{ $r['id'] }}">{{ $r['name'] }} ({{ $r['email'] }})</option>
                        @endforeach
                    </select>
                    @error('recruiterId') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="flex items-end">
                    <label class="flex items-center gap-2 cursor-pointer h-11">
                        <input wire:model="autoApprove" type="checkbox" class="w-4 h-4 rounded border-neutral-300 text-[#464d79] focus:ring-[#464d79]"/>
                        <span class="text-sm font-medium text-neutral-700 dark:text-neutral-300">Auto-approve (go live immediately)</span>
                    </label>
                </div>
            </div>
        </div>
    </div>

    @include('livewire.recruiter.jobs.partials.job-form')
</div>
