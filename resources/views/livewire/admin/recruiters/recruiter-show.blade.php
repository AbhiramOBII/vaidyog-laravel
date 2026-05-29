<div>
    @php $profile = $this->recruiter->profile; @endphp

    {{-- FLASH --}}
    @if(session()->has('message'))
        <div class="mb-4 p-3 rounded-lg bg-[#4ab098]/10 border border-[#4ab098]/20 text-[#4ab098] text-sm font-medium">{{ session('message') }}</div>
    @endif

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-6">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-xl bg-[#464d79]/10 flex items-center justify-center text-[#464d79] font-bold text-xl shrink-0">
                {{ strtoupper(substr($profile?->institution_name ?? 'R', 0, 2)) }}
            </div>
            <div>
                <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">{{ $profile?->institution_name ?? '—' }}</h1>
                <div class="flex items-center gap-2 mt-1.5 flex-wrap">
                    @if($profile?->med_type)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-[#464d79]/10 text-[#464d79] dark:text-indigo-300">{{ $profile->med_type->label() }}</span>
                    @endif
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium {{ $recruiter->status === \App\Enums\UserStatusEnum::Active ? 'bg-green-50 text-green-700' : ($recruiter->status === \App\Enums\UserStatusEnum::Blocked ? 'bg-red-50 text-red-600' : 'bg-amber-50 text-amber-600') }}">{{ $recruiter->status->label() }}</span>
                    @if($profile?->is_featured)
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded text-xs font-medium bg-amber-50 text-amber-600">
                            <svg class="w-3 h-3" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            Featured
                        </span>
                    @endif
                </div>
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('admin.recruiters.edit', $recruiter) }}" wire:navigate class="px-4 py-2 text-sm font-medium bg-white dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-700 rounded-lg hover:bg-neutral-50 transition-colors">Edit</a>
            <button wire:click="toggleFeatured" wire:confirm="Toggle featured status?" class="px-4 py-2 text-sm font-medium bg-white dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-700 rounded-lg hover:bg-neutral-50 transition-colors">{{ $profile?->is_featured ? 'Remove Featured' : 'Mark Featured' }}</button>
            @if($recruiter->status === \App\Enums\UserStatusEnum::Active)
                <button wire:click="changeStatus('blocked')" wire:confirm="Block this recruiter?" class="px-4 py-2 text-sm font-medium text-red-600 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 transition-colors">Block</button>
            @else
                <button wire:click="changeStatus('active')" wire:confirm="Activate this recruiter?" class="px-4 py-2 text-sm font-medium text-[#4ab098] bg-[#4ab098]/10 border border-[#4ab098]/20 rounded-lg hover:bg-[#4ab098]/20 transition-colors">Activate</button>
            @endif
            <a href="{{ route('admin.recruiters.index') }}" wire:navigate class="px-4 py-2 text-sm font-medium text-neutral-500 hover:text-neutral-700 transition-colors">← Back</a>
        </div>
    </div>

    {{-- SUMMARY CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-5">
            <h3 class="text-xs font-semibold text-neutral-500 uppercase tracking-wider mb-3">Contact Details</h3>
            <dl class="space-y-2 text-sm">
                <div><dt class="text-neutral-500 text-xs">Person</dt><dd class="font-medium text-neutral-800 dark:text-neutral-200">{{ $profile?->contact_person_name ?? '—' }}</dd></div>
                <div><dt class="text-neutral-500 text-xs">Email</dt><dd class="font-medium text-neutral-800 dark:text-neutral-200">{{ $profile?->contact_person_email ?? '—' }}</dd></div>
                <div><dt class="text-neutral-500 text-xs">Phone</dt><dd class="font-medium text-neutral-800 dark:text-neutral-200">{{ $profile?->contact_person_phone ?? '—' }}</dd></div>
                <div><dt class="text-neutral-500 text-xs">Website</dt><dd class="font-medium text-neutral-800 dark:text-neutral-200 truncate">{{ $profile?->website_url ?? '—' }}</dd></div>
            </dl>
        </div>
        <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-5">
            <h3 class="text-xs font-semibold text-neutral-500 uppercase tracking-wider mb-3">Account Info</h3>
            <dl class="space-y-2 text-sm">
                <div><dt class="text-neutral-500 text-xs">Login Email</dt><dd class="font-medium text-neutral-800 dark:text-neutral-200">{{ $recruiter->email ?? '—' }}</dd></div>
                <div><dt class="text-neutral-500 text-xs">Auth Provider</dt><dd class="font-medium text-neutral-800 dark:text-neutral-200">{{ $recruiter->auth_provider?->label() ?? '—' }}</dd></div>
                <div><dt class="text-neutral-500 text-xs">Email Verified</dt><dd class="font-medium text-neutral-800 dark:text-neutral-200">{{ $recruiter->email_verified_at ? $recruiter->email_verified_at->format('d M Y') : 'No' }}</dd></div>
                <div><dt class="text-neutral-500 text-xs">Registered</dt><dd class="font-medium text-neutral-800 dark:text-neutral-200">{{ $recruiter->created_at->format('d M Y') }}</dd></div>
            </dl>
        </div>
        <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-5">
            <h3 class="text-xs font-semibold text-neutral-500 uppercase tracking-wider mb-3">Platform Details</h3>
            <dl class="space-y-2 text-sm">
                <div><dt class="text-neutral-500 text-xs">Med Type</dt><dd class="font-medium text-neutral-800 dark:text-neutral-200">{{ $profile?->med_type?->label() ?? '—' }}</dd></div>
                <div><dt class="text-neutral-500 text-xs">Featured</dt><dd class="font-medium text-neutral-800 dark:text-neutral-200">{{ $profile?->is_featured ? 'Yes' : 'No' }}</dd></div>
                <div><dt class="text-neutral-500 text-xs">Referral Code</dt><dd class="font-medium text-neutral-800 dark:text-neutral-200 font-mono">{{ $profile?->referral_code ?? '—' }}</dd></div>
                <div><dt class="text-neutral-500 text-xs">Profile Complete</dt><dd class="font-medium text-neutral-800 dark:text-neutral-200">{{ $profile?->is_profile_completed ? 'Yes' : 'No' }}</dd></div>
            </dl>
        </div>
        <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-5">
            <h3 class="text-xs font-semibold text-neutral-500 uppercase tracking-wider mb-3">Address</h3>
            <dl class="space-y-2 text-sm">
                <div><dd class="font-medium text-neutral-800 dark:text-neutral-200">{{ $profile?->address_line1 ?? '' }}</dd></div>
                @if($profile?->address_line2)<div><dd class="text-neutral-600">{{ $profile->address_line2 }}</dd></div>@endif
                <div><dd class="font-medium text-neutral-800 dark:text-neutral-200">{{ collect([$profile?->city, $profile?->state])->filter()->join(', ') ?: '—' }}</dd></div>
                <div><dt class="text-neutral-500 text-xs">Pincode</dt><dd class="font-medium text-neutral-800 dark:text-neutral-200">{{ $profile?->pincode ?? '—' }}</dd></div>
            </dl>
        </div>
    </div>

    {{-- PROFILE SECTION --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <div class="lg:col-span-2 bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
            <h3 class="text-sm font-semibold text-neutral-900 dark:text-white mb-3">About</h3>
            <p class="text-sm text-neutral-600 dark:text-neutral-400 leading-relaxed">{{ $profile?->description ?? 'No description provided.' }}</p>

            @if($profile?->employee_count)
                <div class="mt-4 pt-4 border-t border-neutral-100 dark:border-neutral-700">
                    <span class="text-xs text-neutral-500">Employees:</span>
                    <span class="text-sm font-medium text-neutral-800 dark:text-neutral-200 ml-1">{{ number_format($profile->employee_count) }}</span>
                </div>
            @endif

            @if(!empty($profile?->specialties))
                <div class="mt-4 pt-4 border-t border-neutral-100 dark:border-neutral-700">
                    <span class="text-xs text-neutral-500 block mb-2">Specialties</span>
                    <div class="flex flex-wrap gap-2">
                        @foreach($profile->specialties as $s)
                            <span class="px-2.5 py-1 rounded-md bg-[#464d79]/10 text-[#464d79] dark:text-indigo-300 text-xs font-medium">{{ $s }}</span>
                        @endforeach
                    </div>
                </div>
            @endif

            @if(!empty($profile?->accreditations))
                <div class="mt-4 pt-4 border-t border-neutral-100 dark:border-neutral-700">
                    <span class="text-xs text-neutral-500 block mb-2">Accreditations</span>
                    <div class="flex flex-wrap gap-2">
                        @foreach($profile->accreditations as $a)
                            <span class="px-2.5 py-1 rounded-md bg-[#4ab098]/10 text-[#4ab098] text-xs font-medium">{{ $a }}</span>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- REFERRAL SECTION --}}
        <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
            <h3 class="text-sm font-semibold text-neutral-900 dark:text-white mb-3">Referral</h3>
            @if($profile?->referral_code)
                <div x-data="{ copied: false }" class="mb-4">
                    <label class="text-xs text-neutral-500 block mb-1">Referral Code</label>
                    <div class="flex items-center gap-2">
                        <code class="flex-1 px-3 py-2 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 rounded-lg text-sm font-mono font-bold text-[#464d79]">{{ $profile->referral_code }}</code>
                        <button @click="navigator.clipboard.writeText('{{ $profile->referral_code }}'); copied = true; setTimeout(() => copied = false, 2000)" class="px-3 py-2 text-xs font-medium bg-neutral-100 dark:bg-neutral-700 rounded-lg hover:bg-neutral-200 transition-colors" x-text="copied ? 'Copied!' : 'Copy'"></button>
                    </div>
                </div>
                <button wire:click="regenerateReferralCode" wire:confirm="Regenerate referral code? The old code will stop working." class="text-xs font-medium text-red-500 hover:text-red-700">Regenerate Code</button>

                @if($recruiter->referrals->count() > 0)
                    <div class="mt-4 pt-4 border-t border-neutral-100 dark:border-neutral-700">
                        <span class="text-xs text-neutral-500 block mb-2">Referrals ({{ $recruiter->referrals->count() }})</span>
                        <div class="space-y-2 max-h-40 overflow-y-auto">
                            @foreach($recruiter->referrals as $ref)
                                <div class="flex items-center justify-between text-xs p-2 rounded bg-neutral-50 dark:bg-neutral-900">
                                    <span class="text-neutral-700 dark:text-neutral-300">{{ $ref->referredUser?->name ?? 'Unknown' }}</span>
                                    <span class="text-neutral-500">{{ $ref->created_at->format('d M Y') }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @else
                <p class="text-sm text-neutral-500">No referral code (not applicable for this med type).</p>
            @endif
        </div>
    </div>

    {{-- FUTURE MODULE PLACEHOLDERS --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-4 opacity-60">
            <div class="text-xs font-medium text-neutral-500 uppercase tracking-wider">Job Postings</div>
            <div class="text-2xl font-bold text-neutral-300 dark:text-neutral-600 mt-1">—</div>
        </div>
        <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-4 opacity-60">
            <div class="text-xs font-medium text-neutral-500 uppercase tracking-wider">Applications</div>
            <div class="text-2xl font-bold text-neutral-300 dark:text-neutral-600 mt-1">—</div>
        </div>
        <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-4 opacity-60">
            <div class="text-xs font-medium text-neutral-500 uppercase tracking-wider">Subscription</div>
            <div class="text-2xl font-bold text-neutral-300 dark:text-neutral-600 mt-1">—</div>
        </div>
        <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-4 opacity-60">
            <div class="text-xs font-medium text-neutral-500 uppercase tracking-wider">Payments</div>
            <div class="text-2xl font-bold text-neutral-300 dark:text-neutral-600 mt-1">—</div>
        </div>
    </div>

    {{-- ADMIN META --}}
    <div class="bg-neutral-50 dark:bg-neutral-900 rounded-xl border border-neutral-200 dark:border-neutral-700 p-4">
        <div class="flex items-center gap-6 text-xs text-neutral-500">
            @if($profile?->createdByAdmin)
                <span>Created by: <strong class="text-neutral-700 dark:text-neutral-300">{{ $profile->createdByAdmin->name }}</strong></span>
            @endif
            <span>Created: {{ $recruiter->created_at->format('d M Y H:i') }}</span>
            <span>Updated: {{ $recruiter->updated_at->format('d M Y H:i') }}</span>
        </div>
    </div>
</div>
