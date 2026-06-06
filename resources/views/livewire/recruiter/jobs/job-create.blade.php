<div x-data="aiMatchingOverlay()" @job-created.window="startProcessing($event.detail)">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">Post New Job</h1>
        <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-1">Fill in the details below. Your job will be reviewed by admin before going live.</p>
    </div>
    @include('livewire.recruiter.jobs.partials.job-form')

    {{-- AI Matching Overlay --}}
    <template x-teleport="body">
        <div x-show="showOverlay" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-[9999] flex items-center justify-center bg-slate-900/95 backdrop-blur-md" style="display:none;">
            <div class="max-w-lg w-full mx-4">
                {{-- Brain Icon with pulse --}}
                <div class="flex justify-center mb-8">
                    <div class="relative">
                        <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-indigo-500 to-emerald-500 flex items-center justify-center shadow-2xl shadow-indigo-500/30" :class="currentStep < 4 && 'animate-pulse'">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.455 2.456L21.75 6l-1.036.259a3.375 3.375 0 00-2.455 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z"/></svg>
                        </div>
                        {{-- Orbiting dots --}}
                        <div x-show="currentStep < 4" class="absolute inset-0 animate-spin" style="animation-duration:3s;">
                            <div class="absolute -top-1 left-1/2 w-2 h-2 rounded-full bg-indigo-400"></div>
                        </div>
                        <div x-show="currentStep < 4" class="absolute inset-0 animate-spin" style="animation-duration:4s; animation-direction:reverse;">
                            <div class="absolute top-1/2 -right-1 w-2 h-2 rounded-full bg-emerald-400"></div>
                        </div>
                    </div>
                </div>

                {{-- Title --}}
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-bold text-white mb-2">AI Talent Match Engine</h2>
                    <p class="text-slate-400 text-sm" x-text="statusText">Initializing...</p>
                </div>

                {{-- Progress bar --}}
                <div class="mb-8 px-4">
                    <div class="h-1.5 bg-slate-700 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-indigo-500 to-emerald-500 rounded-full transition-all duration-700 ease-out" :style="'width:' + progress + '%'"></div>
                    </div>
                    <div class="flex justify-between mt-2">
                        <span class="text-xs text-slate-500" x-text="progress + '%'"></span>
                        <span class="text-xs text-slate-500" x-text="profilesScanned + ' profiles scanned'"></span>
                    </div>
                </div>

                {{-- Steps --}}
                <div class="space-y-3 px-4">
                    <template x-for="(step, idx) in steps" :key="idx">
                        <div class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300" :class="idx < currentStep ? 'bg-emerald-500/10 border border-emerald-500/20' : idx === currentStep ? 'bg-indigo-500/10 border border-indigo-500/20' : 'bg-slate-800/50 border border-slate-700/50'">
                            {{-- Icon --}}
                            <div class="shrink-0">
                                <template x-if="idx < currentStep">
                                    <div class="w-6 h-6 rounded-full bg-emerald-500 flex items-center justify-center">
                                        <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                    </div>
                                </template>
                                <template x-if="idx === currentStep">
                                    <div class="w-6 h-6 rounded-full border-2 border-indigo-500 flex items-center justify-center">
                                        <div class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></div>
                                    </div>
                                </template>
                                <template x-if="idx > currentStep">
                                    <div class="w-6 h-6 rounded-full border-2 border-slate-600 flex items-center justify-center">
                                        <div class="w-2 h-2 rounded-full bg-slate-600"></div>
                                    </div>
                                </template>
                            </div>
                            {{-- Text --}}
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium" :class="idx <= currentStep ? 'text-white' : 'text-slate-500'" x-text="step.title"></p>
                                <p class="text-xs" :class="idx < currentStep ? 'text-emerald-400' : idx === currentStep ? 'text-indigo-400' : 'text-slate-600'" x-text="idx < currentStep ? step.done : idx === currentStep ? step.active : step.pending"></p>
                            </div>
                            {{-- Counter --}}
                            <div x-show="idx <= currentStep && step.count" class="text-right">
                                <p class="text-sm font-bold" :class="idx < currentStep ? 'text-emerald-400' : 'text-indigo-400'" x-text="step.count"></p>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- Match result teaser --}}
                <div x-show="currentStep >= 4" x-transition class="mt-6 px-4">
                    <template x-if="matchedProfiles > 0">
                        <div class="bg-gradient-to-r from-emerald-500/10 to-indigo-500/10 border border-emerald-500/20 rounded-xl p-4 text-center">
                            <p class="text-emerald-400 font-semibold text-sm">Matching Complete</p>
                            <p class="text-white text-lg font-bold mt-1" x-text="matchedProfiles + ' potential candidates identified'"></p>
                            <p class="text-slate-400 text-xs mt-1">You'll be notified as applications come in</p>
                        </div>
                    </template>
                    <template x-if="matchedProfiles === 0">
                        <div class="bg-gradient-to-r from-amber-500/10 to-red-500/10 border border-amber-500/20 rounded-xl p-4 text-center">
                            <p class="text-amber-400 font-semibold text-sm">No Matches Found</p>
                            <p class="text-white text-lg font-bold mt-1">0 candidates match this role</p>
                            <p class="text-slate-400 text-xs mt-1">As new job seekers register, matches will appear on the job page</p>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </template>
</div>

<script>
function aiMatchingOverlay() {
    return {
        showOverlay: false,
        currentStep: 0,
        progress: 0,
        profilesScanned: 0,
        matchedProfiles: 0,
        totalProfiles: 0,
        skillsCount: 0,
        jobSlug: '',
        statusText: 'Initializing AI engine...',
        steps: [
            { title: 'Analyzing Job Requirements', active: 'Extracting skills, qualifications & preferences...', done: 'Requirements mapped', pending: 'Waiting...', count: '' },
            { title: 'Scanning Candidate Database', active: 'Searching healthcare profiles...', done: 'Database scanned', pending: 'Waiting...', count: '' },
            { title: 'Matching & Ranking Profiles', active: 'Applying AI scoring algorithm...', done: 'Profiles ranked', pending: 'Waiting...', count: '' },
            { title: 'Preparing Recommendations', active: 'Building personalized candidate list...', done: 'Recommendations ready', pending: 'Waiting...', count: '' },
        ],
        startProcessing(data) {
            this.totalProfiles = data.totalProfiles || 0;
            this.matchedProfiles = data.matchedProfiles || 0;
            this.skillsCount = data.skillsCount || 0;
            this.jobSlug = data.jobSlug || '';
            this.showOverlay = true;
            this.currentStep = 0;
            this.progress = 0;
            this.profilesScanned = 0;

            // Step 1: Analyzing (0-1.5s)
            this.statusText = 'Analyzing job requirements...';
            this.animateProgress(0, 25, 1500);

            setTimeout(() => {
                this.steps[0].count = this.skillsCount + ' skills';
                this.currentStep = 1;
                this.statusText = 'Scanning candidate database...';
                this.animateCounter('profilesScanned', this.totalProfiles, 2000);
                this.animateProgress(25, 60, 2000);
            }, 1500);

            // Step 2: Scanning (1.5-3.5s)
            setTimeout(() => {
                this.steps[1].count = this.totalProfiles.toLocaleString();
                this.currentStep = 2;
                this.statusText = 'Matching & ranking candidates...';
                this.animateProgress(60, 85, 1500);
            }, 3500);

            // Step 3: Matching (3.5-5s)
            setTimeout(() => {
                this.steps[2].count = this.matchedProfiles + ' matches';
                this.currentStep = 3;
                this.statusText = 'Preparing your recommendations...';
                this.animateProgress(85, 98, 1000);
            }, 5000);

            // Step 4: Done (6s)
            setTimeout(() => {
                this.progress = 100;
                this.steps[3].count = this.matchedProfiles > 0 ? 'Done' : 'No matches';
                this.currentStep = 4;
                this.statusText = this.matchedProfiles > 0 ? 'AI matching complete!' : 'No matching candidates found';
            }, 6000);

            // Redirect to job detail (7.5s)
            setTimeout(() => {
                if (this.jobSlug) {
                    window.location.href = '/recruiter/jobs/' + this.jobSlug;
                } else {
                    window.location.href = '{{ route("recruiter.jobs.index") }}';
                }
            }, 7500);
        },
        animateProgress(from, to, duration) {
            const startTime = Date.now();
            const tick = () => {
                const elapsed = Date.now() - startTime;
                const pct = Math.min(elapsed / duration, 1);
                this.progress = Math.round(from + (to - from) * this.easeOutCubic(pct));
                if (pct < 1) requestAnimationFrame(tick);
            };
            requestAnimationFrame(tick);
        },
        animateCounter(prop, target, duration) {
            const startTime = Date.now();
            const tick = () => {
                const elapsed = Date.now() - startTime;
                const pct = Math.min(elapsed / duration, 1);
                this[prop] = Math.round(target * this.easeOutCubic(pct));
                if (pct < 1) requestAnimationFrame(tick);
            };
            requestAnimationFrame(tick);
        },
        easeOutCubic(t) { return 1 - Math.pow(1 - t, 3); }
    }
}
</script>
