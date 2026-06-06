@use('Illuminate\Support\Facades\Storage')
<div class="max-w-6xl mx-auto">
    @if (!$profile)
        {{-- No profile yet --}}
        <div class="bg-white rounded-xl border border-neutral-200 p-12 text-center">
            <svg class="w-16 h-16 mx-auto text-neutral-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            <h2 class="text-xl font-semibold text-neutral-900 mb-2">Create Your Profile</h2>
            <p class="text-neutral-500 mb-6">Complete your profile to get discovered by recruiters.</p>
            <a href="{{ route('profile.edit') }}" class="inline-flex items-center gap-2 px-6 py-3 text-sm font-medium text-white bg-[#464d79] rounded-lg hover:bg-[#3a4166] transition-colors">Get Started</a>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Left Sidebar --}}
            <div class="space-y-6">
                {{-- Profile Card --}}
                <div class="bg-white rounded-xl border border-neutral-200 p-6 text-center">
                    <img src="{{ $profile->getProfilePictureUrl() }}" class="w-28 h-28 rounded-full mx-auto object-cover border-3 border-neutral-200 mb-4" alt="{{ $profile->getFullName() }}">
                    <h1 class="text-xl font-bold text-neutral-900">{{ $profile->getFullName() }}</h1>
                    @if ($profile->designation)
                        <p class="text-sm text-[#464d79] font-medium mt-1">{{ $profile->designation }}</p>
                        @if ($profile->subdesignation)
                            <p class="text-xs text-neutral-500">{{ $profile->subdesignation }}</p>
                        @endif
                    @endif
                    @if ($profile->city || $profile->state)
                        <p class="text-xs text-neutral-400 mt-2 flex items-center justify-center gap-1">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                            {{ implode(', ', array_filter([$profile->city, $profile->state])) }}
                        </p>
                    @endif
                    @if ($profile->is_open_to_work)
                        <span class="inline-flex items-center gap-1 mt-3 px-3 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-full">
                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span> Open to Work
                        </span>
                    @endif
                    <p class="text-sm text-neutral-600 mt-3 font-medium">{{ $profile->getTotalExperience() }}</p>
                </div>

                {{-- Completeness --}}
                <div class="bg-white rounded-xl border border-neutral-200 p-6">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-sm font-semibold text-neutral-700">Profile Completeness</h3>
                        <span class="text-lg font-bold text-{{ $profile->getCompletenessColor() }}-600">{{ $profile->profile_completeness }}%</span>
                    </div>
                    <div class="w-full bg-neutral-100 rounded-full h-2.5 mb-2">
                        <div class="h-2.5 rounded-full bg-{{ $profile->getCompletenessColor() }}-500 transition-all" style="width: {{ $profile->profile_completeness }}%"></div>
                    </div>
                    <p class="text-xs text-neutral-500 mb-3">{{ $profile->getCompletenessLabel() }}</p>
                    @if (count($incompleteSections) > 0)
                        <div class="space-y-1.5">
                            @foreach (array_slice($incompleteSections, 0, 4) as $section)
                                <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 text-xs text-[#464d79] hover:underline">
                                    <svg class="w-3 h-3 text-neutral-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.586L7.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 10.586V7z" clip-rule="evenodd"/></svg>
                                    Complete {{ $section }}
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Quick Actions --}}
                <div class="bg-white rounded-xl border border-neutral-200 p-6 space-y-3">
                    <a href="{{ route('profile.edit') }}" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium text-white bg-[#464d79] rounded-lg hover:bg-[#3a4166] transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Edit Profile
                    </a>
                    @if ($profile->profile_slug)
                        <a href="{{ route('profile.public', $profile->profile_slug) }}" target="_blank" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium text-[#4ab098] border border-[#4ab098]/30 rounded-lg hover:bg-[#4ab098]/5 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            View Public Profile
                        </a>
                        <p class="text-[11px] text-neutral-400 text-center truncate px-1">
                            /profile/{{ $profile->profile_slug }}
                        </p>
                    @endif
                    @if ($profile->getResumeUrl())
                        <a href="{{ $profile->getResumeUrl() }}" target="_blank" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium text-[#464d79] border border-[#464d79]/30 rounded-lg hover:bg-[#464d79]/5 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            Download Resume
                        </a>
                    @else
                        <a href="{{ route('profile.edit') }}" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium text-neutral-600 border border-neutral-300 rounded-lg hover:bg-neutral-50 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                            Upload Resume
                        </a>
                    @endif
                </div>

                {{-- Billing & Subscription --}}
                <div class="bg-white rounded-xl border border-neutral-200 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-semibold text-neutral-700">Billing & Plan</h3>
                        <a href="{{ route('jobseeker.plan') }}" class="text-xs text-[#464d79] hover:underline">Manage</a>
                    </div>

                    @if($activeSub)
                    <div class="flex items-center gap-2 mb-3">
                        <span class="text-sm font-bold text-neutral-900">{{ $activeSub->plan_name }}</span>
                        <span class="text-[10px] px-2 py-0.5 rounded-full {{ $activeSub->ranking->getBadgeClasses() }}">{{ $activeSub->ranking->getLabel() }}</span>
                    </div>

                    <div class="space-y-2 text-xs">
                        <div class="flex justify-between">
                            <span class="text-neutral-500">Applications</span>
                            <span class="font-medium text-neutral-800">
                                @if($remaining === 'unlimited')
                                    Unlimited
                                @else
                                    {{ $remaining }} remaining
                                @endif
                            </span>
                        </div>
                        @if($remaining !== 'unlimited' && $activeSub->applications_per_month)
                        @php $used = $activeSub->applications_per_month - $remaining; $pct = ($used / $activeSub->applications_per_month) * 100; @endphp
                        <div class="w-full h-1.5 bg-neutral-100 rounded-full overflow-hidden">
                            <div class="h-full rounded-full transition-all {{ $pct > 80 ? 'bg-red-500' : ($pct > 50 ? 'bg-amber-500' : 'bg-[#4ab098]') }}" style="width: {{ min($pct, 100) }}%"></div>
                        </div>
                        @endif
                        <div class="flex justify-between">
                            <span class="text-neutral-500">Expires</span>
                            <span class="font-medium text-neutral-800">{{ $activeSub->expires_at?->format('M j, Y') ?? 'Never' }}</span>
                        </div>
                    </div>
                    @else
                    <div class="text-center py-2">
                        <span class="text-sm font-bold text-neutral-900">Basic (Free)</span>
                        <p class="text-xs text-neutral-500 mt-1">{{ $remaining }} apps/month</p>
                    </div>
                    @endif

                    <div class="mt-4 pt-3 border-t border-neutral-100">
                        <a href="{{ route('plans.index') }}" class="w-full flex items-center justify-center gap-2 px-4 py-2 text-xs font-semibold text-white rounded-lg transition-colors" style="background: linear-gradient(90deg, #464d79 0%, #48B098 100%);">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                            {{ $activeSub ? 'Upgrade Plan' : 'View Plans' }}
                        </a>
                    </div>

                    @if($recentPayments->isNotEmpty())
                    <div class="mt-4 pt-3 border-t border-neutral-100">
                        <h4 class="text-xs font-semibold text-neutral-500 uppercase mb-2">Recent Payments</h4>
                        <div class="space-y-2">
                            @foreach($recentPayments as $payment)
                            <div class="flex items-center justify-between text-xs">
                                <div>
                                    <span class="font-medium text-neutral-800">₹{{ number_format($payment->amount) }}</span>
                                    <span class="text-neutral-400 ml-1">{{ $payment->paid_at?->format('M j') ?? 'Pending' }}</span>
                                </div>
                                <span class="px-1.5 py-0.5 rounded text-[10px] font-medium {{ $payment->status->getBadgeClasses() }}">{{ $payment->status->label() }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Right Main Content --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Onboarding Banner --}}
                @if ($profile->profile_completeness < 30)
                    <div class="bg-gradient-to-r from-[#464d79] to-[#4ab098] rounded-xl p-6 text-white">
                        <h3 class="text-lg font-semibold mb-1">Your profile is {{ $profile->profile_completeness }}% complete</h3>
                        <p class="text-sm text-white/80 mb-4">Complete your profile to get discovered by healthcare recruiters.</p>
                        <div class="w-full bg-white/20 rounded-full h-2 mb-3">
                            <div class="h-2 rounded-full bg-white transition-all" style="width: {{ $profile->profile_completeness }}%"></div>
                        </div>
                        <a href="{{ route('profile.edit') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-[#464d79] bg-white rounded-lg hover:bg-neutral-100 transition-colors">Complete Profile</a>
                    </div>
                @endif

                {{-- About --}}
                <div class="bg-white rounded-xl border border-neutral-200 p-6">
                    <div class="flex items-center justify-between mb-3">
                        <h2 class="text-lg font-semibold text-neutral-900">About</h2>
                        <a href="{{ route('profile.edit') }}" class="text-xs text-[#464d79] hover:underline">Edit</a>
                    </div>
                    @if ($profile->about)
                        <p class="text-sm text-neutral-600 leading-relaxed whitespace-pre-line">{{ $profile->about }}</p>
                    @else
                        <p class="text-sm text-neutral-400 italic">Add a summary about yourself to stand out to recruiters.</p>
                    @endif
                </div>

                {{-- Skills --}}
                <div class="bg-white rounded-xl border border-neutral-200 p-6">
                    <div class="flex items-center justify-between mb-3">
                        <h2 class="text-lg font-semibold text-neutral-900">Skills</h2>
                        <a href="{{ route('profile.edit') }}" class="text-xs text-[#464d79] hover:underline">Edit</a>
                    </div>
                    @if ($profile->key_skills && count($profile->key_skills) > 0)
                        <div class="flex flex-wrap gap-2">
                            @foreach ($profile->key_skills as $skill)
                                <span class="px-3 py-1.5 text-sm font-medium text-[#4ab098] bg-[#4ab098]/10 rounded-full">{{ $skill }}</span>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-neutral-400 italic">No skills added yet.</p>
                    @endif
                </div>

                {{-- Languages --}}
                <div class="bg-white rounded-xl border border-neutral-200 p-6">
                    <div class="flex items-center justify-between mb-3">
                        <h2 class="text-lg font-semibold text-neutral-900">Languages</h2>
                    </div>
                    @livewire('job-seeker.profile.sub-models.language-manager')
                </div>

                {{-- Certifications --}}
                <div class="bg-white rounded-xl border border-neutral-200 p-6">
                    <div class="flex items-center justify-between mb-3">
                        <h2 class="text-lg font-semibold text-neutral-900">Certifications</h2>
                    </div>
                    @livewire('job-seeker.profile.sub-models.certification-manager')
                </div>

                {{-- Education --}}
                <div class="bg-white rounded-xl border border-neutral-200 p-6">
                    <div class="flex items-center justify-between mb-3">
                        <h2 class="text-lg font-semibold text-neutral-900">Education</h2>
                    </div>
                    @livewire('job-seeker.profile.sub-models.education-manager')
                </div>

                {{-- Work Experience --}}
                <div class="bg-white rounded-xl border border-neutral-200 p-6">
                    <div class="flex items-center justify-between mb-3">
                        <h2 class="text-lg font-semibold text-neutral-900">Work Experience</h2>
                    </div>
                    @livewire('job-seeker.profile.sub-models.employment-manager')
                </div>

                {{-- Projects --}}
                <div class="bg-white rounded-xl border border-neutral-200 p-6">
                    <div class="flex items-center justify-between mb-3">
                        <h2 class="text-lg font-semibold text-neutral-900">Projects</h2>
                    </div>
                    @livewire('job-seeker.profile.sub-models.project-manager')
                </div>

                {{-- Recognitions --}}
                <div class="bg-white rounded-xl border border-neutral-200 p-6" x-data="{ open: false }">
                    <button @click="open = !open" class="w-full flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-neutral-900">Recognitions</h2>
                        <svg class="w-5 h-5 text-neutral-400 transition-transform" :class="open && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open" x-collapse class="mt-4 space-y-6 divide-y divide-neutral-100">
                        <div class="pt-4">
                            <h3 class="text-sm font-semibold text-neutral-700 mb-2">Publications</h3>
                            @livewire('job-seeker.profile.recognitions.publication-manager')
                        </div>
                        <div class="pt-4">
                            <h3 class="text-sm font-semibold text-neutral-700 mb-2">Presentations & Seminars</h3>
                            @livewire('job-seeker.profile.recognitions.presentation-manager')
                        </div>
                        <div class="pt-4">
                            <h3 class="text-sm font-semibold text-neutral-700 mb-2">Research</h3>
                            @livewire('job-seeker.profile.recognitions.research-manager')
                        </div>
                        <div class="pt-4">
                            <h3 class="text-sm font-semibold text-neutral-700 mb-2">Honours & Awards</h3>
                            @livewire('job-seeker.profile.recognitions.honour-manager')
                        </div>
                        <div class="pt-4">
                            <h3 class="text-sm font-semibold text-neutral-700 mb-2">Affiliations</h3>
                            @livewire('job-seeker.profile.recognitions.affiliation-manager')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
