<div>
    @if($success)
    <div class="text-center py-6">
        <div class="w-12 h-12 mx-auto bg-green-100 rounded-full flex items-center justify-center mb-3">
            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        </div>
        <p class="text-lg font-semibold text-neutral-900 dark:text-white">Application Submitted!</p>
        <p class="text-sm text-neutral-500 mt-1">Good luck! You can track your application status in "My Applications".</p>
    </div>
    @elseif($alreadyApplied)
    <div class="text-center py-6">
        <p class="text-sm text-amber-600 font-medium">You have already applied to this job.</p>
    </div>
    @else
    <div class="space-y-5">
        <h3 class="text-lg font-semibold text-neutral-900 dark:text-white">Apply for {{ $job?->job_title }}</h3>

        @if($errorMessage)
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
            {{ $errorMessage }}
            @if($showUpgradeCta)
            <a href="{{ route('plans.index') }}" class="mt-2 inline-block text-[#464d79] font-semibold hover:underline">View Plans &rarr;</a>
            @endif
        </div>
        @endif

        {{-- Applicant snapshot --}}
        <div class="bg-neutral-50 dark:bg-neutral-900 rounded-lg p-4 text-sm">
            <p class="font-medium text-neutral-900 dark:text-white">{{ auth()->user()->name }}</p>
            <p class="text-neutral-500">{{ auth()->user()->phone ?? 'No phone' }} &middot; {{ auth()->user()->jobSeekerProfile?->category_name ?? 'No category' }}</p>
        </div>

        {{-- Resume --}}
        <div>
            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Resume</label>
            @if(auth()->user()->jobSeekerProfile?->resume_path)
            <div class="flex items-center gap-3 mb-2">
                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox" wire:model="useProfileResume" class="rounded border-neutral-300" checked/>
                    <span class="text-neutral-700 dark:text-neutral-300">Use profile resume</span>
                </label>
            </div>
            @endif
            <input wire:model="resumeFile" type="file" accept=".pdf" class="w-full text-sm text-neutral-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-[#464d79]/10 file:text-[#464d79] hover:file:bg-[#464d79]/20"/>
            <p class="text-xs text-neutral-500 mt-1">PDF only, max 5MB. Optional but recommended.</p>
            @error('resumeFile') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            <div wire:loading wire:target="resumeFile" class="text-xs text-[#464d79] mt-1">Uploading...</div>
        </div>

        {{-- Cover Note --}}
        <div x-data="{ chars: 0 }">
            <label class="block text-sm font-medium text-neutral-700 dark:text-neutral-300 mb-1">Cover Note <span class="text-neutral-400">(optional)</span></label>
            <textarea wire:model="coverNote" rows="4" maxlength="500"
                @input="chars = $event.target.value.length"
                placeholder="Tell the recruiter why you're a great fit..."
                class="w-full px-3 py-2 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 rounded-lg text-sm focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20"></textarea>
            <p class="text-xs text-neutral-400 text-right mt-1"><span x-text="chars">0</span>/500</p>
            @error('coverNote') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Submit --}}
        <div class="flex justify-end gap-3 pt-2">
            <button @click="$dispatch('close-modal')" type="button" class="px-4 h-10 text-sm font-medium text-neutral-600 border border-neutral-200 dark:border-neutral-700 rounded-lg hover:bg-neutral-50 transition-colors">Cancel</button>
            <button wire:click="submit" wire:loading.attr="disabled" class="px-6 h-10 bg-[#464d79] text-white text-sm font-medium rounded-lg hover:bg-[#464d79]/90 transition-colors disabled:opacity-50">
                <span wire:loading.remove wire:target="submit">Submit Application</span>
                <span wire:loading wire:target="submit">Submitting...</span>
            </button>
        </div>
    </div>
    @endif
</div>
