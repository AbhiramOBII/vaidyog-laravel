<div>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">Edit Job (Admin)</h1>
        <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-1">Editing as admin does not require re-approval.</p>
    </div>

    {{-- Override approval toggle --}}
    @if(!$job->admin_approved)
    <div class="mb-6 p-4 rounded-xl bg-[#464d79]/5 border border-[#464d79]/20">
        <label class="flex items-center gap-3 cursor-pointer">
            <input wire:model="overrideApproval" type="checkbox" class="w-4 h-4 rounded border-neutral-300 text-[#464d79] focus:ring-[#464d79]"/>
            <div>
                <span class="text-sm font-medium text-neutral-900 dark:text-white">Override: Approve this job on save</span>
                <p class="text-xs text-neutral-500 mt-0.5">This will approve and make the job live when you save.</p>
            </div>
        </label>
    </div>
    @endif

    @include('livewire.recruiter.jobs.partials.job-form')
</div>
