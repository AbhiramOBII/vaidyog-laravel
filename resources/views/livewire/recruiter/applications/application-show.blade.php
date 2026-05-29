<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">{{ $application->applicant?->name }}</h1>
            <p class="text-sm text-neutral-500 mt-1">Applied to: {{ $application->job?->job_title }}</p>
        </div>
        <a href="{{ route('recruiter.applications.for-job', $application->job) }}" wire:navigate class="text-sm text-[#464d79] hover:underline">&larr; Back</a>
    </div>

    @if(session('message'))
    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">{{ session('message') }}</div>
    @endif
    @if(session('error'))
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">{{ session('error') }}</div>
    @endif

    {{-- Status & Ranking badges --}}
    <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
        <div class="flex items-center gap-4">
            <span class="inline-flex px-3 py-1 rounded-full text-sm font-medium {{ $application->status->getBadgeClasses() }}">{{ $application->status->label() }}</span>
            <span class="inline-flex px-2 py-0.5 rounded text-xs font-bold {{ $application->ranking->getBadgeClasses() }}">{{ $application->ranking->value }} — {{ $application->ranking->getLabel() }}</span>
            @if($application->resume_path)
            <a href="{{ asset('storage/' . $application->resume_path) }}" target="_blank" class="ml-auto text-xs font-medium text-[#464d79] hover:underline">Download Resume</a>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Left: Applicant profile --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Contact --}}
            <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
                <h3 class="text-sm font-semibold text-neutral-500 uppercase mb-3">Applicant Profile</h3>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div><span class="text-neutral-500 block text-xs">Name</span><span class="text-neutral-900 dark:text-white">{{ $application->applicant?->name }}</span></div>
                    <div><span class="text-neutral-500 block text-xs">Phone</span><span class="text-neutral-900 dark:text-white">{{ $application->applicant?->phone ?? '—' }}</span></div>
                    <div><span class="text-neutral-500 block text-xs">Category</span><span class="text-neutral-900 dark:text-white">{{ $application->applicant?->jobSeekerProfile?->category_name ?? '—' }}</span></div>
                    <div><span class="text-neutral-500 block text-xs">Experience</span><span class="text-neutral-900 dark:text-white">{{ $application->applicant?->jobSeekerProfile?->experience_years ?? '—' }} yrs</span></div>
                </div>
            </div>

            {{-- Cover note --}}
            @if($application->cover_note)
            <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
                <h3 class="text-sm font-semibold text-neutral-500 uppercase mb-3">Cover Note</h3>
                <p class="text-sm text-neutral-700 dark:text-neutral-300">{{ $application->cover_note }}</p>
            </div>
            @endif

            {{-- Matching skills --}}
            @if($application->matching_skills)
            <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
                <h3 class="text-sm font-semibold text-neutral-500 uppercase mb-3">Matching Skills ({{ count($application->matching_skills) }})</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($application->matching_skills as $skill)
                    <span class="px-2.5 py-1 text-xs font-medium bg-[#4ab098]/10 text-[#4ab098] rounded-lg">{{ $skill }}</span>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Status Timeline --}}
            <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
                <h3 class="text-sm font-semibold text-neutral-500 uppercase mb-3">Status Timeline</h3>
                <div class="space-y-3">
                    @foreach($application->getStatusHistory() as $status => $timestamp)
                    <div class="flex items-center gap-3">
                        <div class="w-2.5 h-2.5 rounded-full bg-[#4ab098]"></div>
                        <span class="text-sm font-medium text-neutral-900 dark:text-white capitalize">{{ $status }}</span>
                        <span class="text-xs text-neutral-500">{{ \Carbon\Carbon::parse($timestamp)->format('M d, Y H:i') }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Right: Actions --}}
        <div class="space-y-6">
            {{-- Update Status --}}
            @if(!empty($allowedStatuses))
            <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
                <h3 class="text-sm font-semibold text-neutral-500 uppercase mb-3">Update Status</h3>
                <select wire:model="newStatus" class="w-full h-10 px-3 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 rounded-lg text-sm mb-3">
                    <option value="">Select status...</option>
                    @foreach($allowedStatuses as $next)
                    <option value="{{ $next }}">{{ ucfirst($next) }}</option>
                    @endforeach
                </select>
                <button wire:click="updateStatus" class="w-full h-10 bg-[#464d79] text-white text-sm font-medium rounded-lg hover:bg-[#464d79]/90 transition-colors">Update</button>
            </div>
            @endif

            {{-- Recruiter Notes --}}
            <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
                <h3 class="text-sm font-semibold text-neutral-500 uppercase mb-3">Internal Notes</h3>
                <textarea wire:model="recruiterNotes" rows="4" class="w-full px-3 py-2 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 rounded-lg text-sm focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20" placeholder="Notes visible only to you..."></textarea>
                <button wire:click="saveNotes" class="mt-2 px-4 h-9 bg-neutral-100 dark:bg-neutral-700 border border-neutral-200 dark:border-neutral-600 text-sm font-medium rounded-lg hover:bg-neutral-200 transition-colors">Save Notes</button>
            </div>

            {{-- Interview stub --}}
            <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 opacity-60">
                <h3 class="text-sm font-semibold text-neutral-500 uppercase mb-3">Interview</h3>
                <p class="text-xs text-neutral-500 mb-3">Schedule an interview with this applicant.</p>
                <button disabled class="w-full h-10 bg-neutral-100 dark:bg-neutral-700 text-neutral-400 text-sm font-medium rounded-lg cursor-not-allowed">Schedule Interview (Coming Soon)</button>
            </div>
        </div>
    </div>
</div>
