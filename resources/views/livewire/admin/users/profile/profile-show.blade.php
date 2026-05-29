<div class="max-w-5xl mx-auto space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-neutral-900">User Profile</h1>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.users.profile.edit', $userId) }}" class="px-4 py-2 text-sm font-medium text-white bg-[#464d79] rounded-lg hover:bg-[#3a4166] transition-colors">Edit Profile</a>
        </div>
    </div>

    @if (!$profile)
        <div class="bg-white rounded-xl border border-neutral-200 p-8 text-center">
            <p class="text-neutral-500">This user has not created a job seeker profile yet.</p>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Sidebar --}}
            <div class="space-y-6">
                <div class="bg-white rounded-xl border border-neutral-200 p-6 text-center">
                    <img src="{{ $profile->getProfilePictureUrl() }}" class="w-24 h-24 rounded-full mx-auto object-cover border-2 border-neutral-200 mb-4" alt="{{ $profile->getFullName() }}">
                    <h2 class="text-lg font-bold text-neutral-900">{{ $profile->getFullName() }}</h2>
                    <p class="text-xs text-neutral-500 mt-0.5">{{ $user->email }}</p>
                    @if ($profile->designation)
                        <p class="text-sm text-[#464d79] font-medium mt-2">{{ $profile->designation }}</p>
                    @endif
                    @if ($profile->city || $profile->state)
                        <p class="text-xs text-neutral-400 mt-1">{{ implode(', ', array_filter([$profile->city, $profile->state])) }}</p>
                    @endif
                    <div class="flex items-center justify-center gap-2 mt-3">
                        @if ($profile->is_open_to_work)
                            <span class="px-2 py-0.5 text-xs font-medium text-green-700 bg-green-100 rounded-full">Open to Work</span>
                        @else
                            <span class="px-2 py-0.5 text-xs font-medium text-neutral-600 bg-neutral-100 rounded-full">Not Seeking</span>
                        @endif
                    </div>
                </div>

                {{-- Admin Meta --}}
                <div class="bg-white rounded-xl border border-neutral-200 p-5 space-y-3">
                    <h3 class="text-sm font-semibold text-neutral-700">Profile Metadata</h3>
                    <div class="text-xs text-neutral-600 space-y-2">
                        <div class="flex justify-between"><span>Completeness</span><span class="font-medium">{{ $profile->profile_completeness }}%</span></div>
                        <div class="flex justify-between"><span>Experience</span><span class="font-medium">{{ $profile->getTotalExperience() }}</span></div>
                        <div class="flex justify-between"><span>User ID</span><span class="font-mono">{{ $user->id }}</span></div>
                        <div class="flex justify-between"><span>Joined</span><span>{{ $user->created_at->format('d M Y') }}</span></div>
                        <div class="flex justify-between"><span>Phone</span><span>{{ $profile->phone ?? '—' }}</span></div>
                    </div>
                </div>
            </div>

            {{-- Main --}}
            <div class="lg:col-span-2 space-y-6">
                @if ($profile->about)
                    <div class="bg-white rounded-xl border border-neutral-200 p-6">
                        <h2 class="text-lg font-semibold text-neutral-900 mb-2">About</h2>
                        <p class="text-sm text-neutral-600 whitespace-pre-line">{{ $profile->about }}</p>
                    </div>
                @endif

                @if ($profile->key_skills && count($profile->key_skills))
                    <div class="bg-white rounded-xl border border-neutral-200 p-6">
                        <h2 class="text-lg font-semibold text-neutral-900 mb-3">Skills</h2>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($profile->key_skills as $skill)
                                <span class="px-3 py-1 text-sm text-[#4ab098] bg-[#4ab098]/10 rounded-full">{{ $skill }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if ($employments->count())
                    <div class="bg-white rounded-xl border border-neutral-200 p-6">
                        <h2 class="text-lg font-semibold text-neutral-900 mb-3">Employment ({{ $employments->count() }})</h2>
                        <div class="space-y-3">
                            @foreach ($employments as $emp)
                                <div class="border-l-2 border-[#464d79]/20 pl-4">
                                    <h4 class="text-sm font-semibold text-neutral-900">{{ $emp->job_title }}</h4>
                                    <p class="text-xs text-neutral-600">{{ $emp->company_name }} &middot; {{ $emp->getDurationLabel() }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if ($educations->count())
                    <div class="bg-white rounded-xl border border-neutral-200 p-6">
                        <h2 class="text-lg font-semibold text-neutral-900 mb-3">Education ({{ $educations->count() }})</h2>
                        <div class="space-y-3">
                            @foreach ($educations as $edu)
                                <div class="border-l-2 border-[#4ab098]/20 pl-4">
                                    <h4 class="text-sm font-semibold text-neutral-900">{{ $edu->degree }}</h4>
                                    <p class="text-xs text-neutral-600">{{ $edu->university }} &middot; {{ $edu->getDurationLabel() }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if ($certifications->count())
                    <div class="bg-white rounded-xl border border-neutral-200 p-6">
                        <h2 class="text-lg font-semibold text-neutral-900 mb-3">Certifications ({{ $certifications->count() }})</h2>
                        <div class="space-y-2">
                            @foreach ($certifications as $cert)
                                <p class="text-sm text-neutral-800">{{ $cert->name }} @if($cert->medical_institute)<span class="text-neutral-400">— {{ $cert->medical_institute }}</span>@endif</p>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if ($languages->count())
                    <div class="bg-white rounded-xl border border-neutral-200 p-6">
                        <h2 class="text-lg font-semibold text-neutral-900 mb-3">Languages ({{ $languages->count() }})</h2>
                        <div class="flex flex-wrap gap-3">
                            @foreach ($languages as $lang)
                                <span class="px-3 py-1 text-sm bg-neutral-50 border rounded-lg">{{ $lang->name }} <span class="text-neutral-400">({{ ucfirst($lang->proficiency) }})</span></span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>
