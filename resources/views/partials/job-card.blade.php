<a href="{{ route('jobs.show', $job) }}" class="group block bg-white rounded-xl border border-gray-200 hover:border-primary-200 p-5 transition-all duration-200 hover:shadow-md {{ ($featured ?? false) ? 'ring-1 ring-amber-200' : '' }}">
    @php $logo = $job->recruiter?->profile?->logo_path; @endphp

    {{-- Row 1: Logo + Hospital Name + Status --}}
    <div class="flex items-center gap-3 mb-3">
        @if($logo)
            <img src="{{ asset('storage/' . $logo) }}" alt="{{ $job->institution_name }}" class="h-8 max-w-[120px] object-contain shrink-0"/>
        @else
            <div class="h-8 px-3 rounded-lg bg-gradient-to-br from-primary-100 to-primary-50 border border-primary-200 flex items-center justify-center shrink-0">
                <span class="text-xs font-bold text-primary-600 tracking-wide">{{ strtoupper(substr($job->institution_name ?? 'V', 0, 2)) }}</span>
            </div>
        @endif
        <span class="text-sm font-semibold text-gray-700 truncate">{{ $job->institution_name ?? 'Confidential' }}</span>
        @if($featured ?? false)
        <span class="ml-auto shrink-0 inline-flex items-center bg-amber-50 text-amber-600 border border-amber-200 text-[10px] font-semibold px-2 py-0.5 rounded-full">Featured</span>
        @endif
    </div>

    {{-- Row 2: Job Title --}}
    <h3 class="text-base font-bold text-gray-900 leading-snug mb-3 group-hover:text-primary-600 transition-colors">{{ $job->job_title }}</h3>

    {{-- Row 3: Location · Job Type · Experience --}}
    <div class="flex flex-wrap items-center gap-1.5 mb-3">
        @if($job->location_city)
        <span class="inline-flex items-center gap-1 bg-gray-50 text-gray-600 text-[11px] px-2.5 py-1 rounded-md">
            <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
            {{ $job->location_city }}@if($job->location_state), {{ $job->location_state }}@endif
        </span>
        @endif
        @if($job->employment_type)
        <span class="inline-flex items-center gap-1 bg-gray-50 text-gray-600 text-[11px] px-2.5 py-1 rounded-md">
            <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ ucfirst(str_replace('_', ' ', $job->employment_type->value ?? $job->employment_type)) }}
        </span>
        @endif
        @if($job->experience_min || $job->experience_max)
        <span class="inline-flex items-center gap-1 bg-gray-50 text-gray-600 text-[11px] px-2.5 py-1 rounded-md">
            <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            {{ $job->experience_min ?? 0 }}-{{ $job->experience_max ?? '?' }} yrs
        </span>
        @endif
    </div>

    {{-- Row 4: Salary --}}
    <div class="mb-3">
        <span class="text-sm font-semibold text-gray-800">
            @if($job->salary_min && $job->salary_max)
                ₹{{ number_format($job->salary_min / 100000, 1) }}L - ₹{{ number_format($job->salary_max / 100000, 1) }}L <span class="text-xs font-normal text-gray-400">PA</span>
            @elseif($job->salary_min)
                ₹{{ number_format($job->salary_min / 100000, 1) }}L+ <span class="text-xs font-normal text-gray-400">PA</span>
            @else
                <span class="text-xs font-normal text-gray-400">Salary not disclosed</span>
            @endif
        </span>
    </div>

    {{-- Row 5: Specialty + Skills --}}
    <div class="flex flex-wrap items-center gap-1.5 mb-4">
        @if($job->specialty)
        <span class="bg-primary-50 text-primary-600 text-[10px] font-semibold px-2.5 py-0.5 rounded-full">{{ $job->specialty->name }}</span>
        @endif
        @if($job->key_skills && is_array($job->key_skills))
            @foreach(array_slice($job->key_skills, 0, 3) as $skill)
            <span class="bg-gray-100 text-gray-500 text-[10px] px-2 py-0.5 rounded-full">{{ $skill }}</span>
            @endforeach
            @if(count($job->key_skills) > 3)
            <span class="text-[10px] text-gray-400">+{{ count($job->key_skills) - 3 }}</span>
            @endif
        @endif
    </div>

    {{-- Row 6: Posted date + CTA --}}
    <div class="flex items-center justify-between pt-3 border-t border-gray-100">
        <span class="text-[11px] text-gray-400">{{ $job->approved_at ? $job->approved_at->diffForHumans() : 'Recently' }}</span>
        <span class="inline-flex items-center gap-1 text-xs font-semibold text-teal-500 group-hover:text-teal-600 transition-colors">
            View Details
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </span>
    </div>
</a>
