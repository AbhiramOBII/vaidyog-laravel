@section('title', 'Best Healthcare Jobs in India')
@section('description', 'Browse top healthcare jobs for doctors, nurses, pharmacists & allied health professionals across India. Find your next opportunity at leading hospitals & clinics.')

@push('schema')
    @include('partials.schema.breadcrumb', ['breadcrumbs' => [
        ['name' => 'Home', 'url' => url('/')],
        ['name' => 'Healthcare Jobs', 'url' => route('jobs.index')],
    ]])
@endpush

<div>
    {{-- Page header --}}
    @include('partials.page-hero', ['title' => 'Find Healthcare Jobs', 'subtitle' => 'Browse opportunities across hospitals, clinics and research centres'])

    {{-- Search bar --}}
    <div class="bg-white border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5">
            <div class="bg-gray-50 rounded-xl p-3">
                <div class="grid grid-cols-1 sm:grid-cols-12 gap-2">
                    <div class="sm:col-span-5 relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <input wire:model.live.debounce.400ms="search" type="text" placeholder="Job title, skills, institution..." class="w-full pl-9 pr-4 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 focus:bg-white transition-colors"/>
                    </div>
                    <div class="sm:col-span-3 relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                        <input wire:model.live.debounce.400ms="city" type="text" placeholder="City or State..." class="w-full pl-9 pr-4 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 focus:bg-white transition-colors"/>
                    </div>
                    <div class="sm:col-span-2">
                        <select wire:model.live="exp" class="w-full px-3 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 focus:bg-white transition-colors appearance-none">
                            <option value="">Experience</option>
                            <option value="0-1">Fresher (0-1 yr)</option>
                            <option value="1-3">1-3 years</option>
                            <option value="3-5">3-5 years</option>
                            <option value="5-10">5-10 years</option>
                            <option value="10+">10+ years</option>
                        </select>
                    </div>
                    <div class="sm:col-span-2">
                        <button wire:click="$refresh" class="w-full py-2.5 px-4 bg-teal-500 text-white text-sm font-semibold rounded-lg hover:bg-teal-600 transition-colors">Search</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Main content --}}
    <div class="bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

            {{-- Active filter chips --}}
            @if($search || $city || $type || $exp || $category || !empty($specialty))
            <div class="flex flex-wrap items-center gap-2 mb-5">
                <span class="text-xs text-gray-500 font-medium mr-1">Active filters:</span>
                @if($search)
                <button wire:click="clearFilter('search')" class="group inline-flex items-center gap-1.5 bg-white text-primary-600 border border-primary-200 text-xs font-medium px-3 py-1.5 rounded-full hover:bg-primary-50 transition-colors">
                    {{ $search }}
                    <svg class="w-3 h-3 text-primary-400 group-hover:text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
                @endif
                @if($city)
                <button wire:click="clearFilter('city')" class="group inline-flex items-center gap-1.5 bg-white text-primary-600 border border-primary-200 text-xs font-medium px-3 py-1.5 rounded-full hover:bg-primary-50 transition-colors">
                    {{ $city }}
                    <svg class="w-3 h-3 text-primary-400 group-hover:text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
                @endif
                @if($type)
                <button wire:click="clearFilter('type')" class="group inline-flex items-center gap-1.5 bg-white text-primary-600 border border-primary-200 text-xs font-medium px-3 py-1.5 rounded-full hover:bg-primary-50 transition-colors">
                    {{ ucfirst(str_replace('_', ' ', $type)) }}
                    <svg class="w-3 h-3 text-primary-400 group-hover:text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
                @endif
                @if($exp)
                <button wire:click="clearFilter('exp')" class="group inline-flex items-center gap-1.5 bg-white text-primary-600 border border-primary-200 text-xs font-medium px-3 py-1.5 rounded-full hover:bg-primary-50 transition-colors">
                    {{ $exp }} yrs
                    <svg class="w-3 h-3 text-primary-400 group-hover:text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
                @endif
                @foreach($specialty as $specId)
                <button wire:click="toggleSpecialty({{ $specId }})" class="group inline-flex items-center gap-1.5 bg-white text-primary-600 border border-primary-200 text-xs font-medium px-3 py-1.5 rounded-full hover:bg-primary-50 transition-colors">
                    {{ $specialties->firstWhere('id', $specId)?->name ?? 'Specialty' }}
                    <svg class="w-3 h-3 text-primary-400 group-hover:text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
                @endforeach
                <button wire:click="clearAll" class="text-xs text-red-500 hover:text-red-700 font-semibold ml-1 transition-colors">Clear All</button>
            </div>
            @endif

            {{-- Results toolbar --}}
            <div class="flex items-center justify-between mb-5">
                <p class="text-sm text-gray-600"><span class="font-semibold text-gray-900">{{ number_format($totalCount) }}</span> jobs found</p>
                <div class="flex items-center gap-2">
                    <span class="text-xs text-gray-400 hidden sm:inline">Sort by</span>
                    <select wire:model.live="sort" class="text-sm bg-white border border-gray-200 rounded-lg px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500">
                        <option value="latest">Date Posted</option>
                        <option value="salary">Highest Salary</option>
                        <option value="experience">Experience</option>
                    </select>
                </div>
            </div>

            {{-- Two-column layout --}}
            <div class="flex gap-6">

                {{-- LEFT: Filter sidebar --}}
                <aside class="hidden lg:block w-[260px] shrink-0" x-data="{ showType: true, showLoc: true, showExp: true, showSpec: true }">
                    <div class="sticky top-20 space-y-4">
                        <div class="bg-white rounded-xl border border-gray-200 p-5">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-sm font-bold text-gray-900">Filters</h3>
                                <button wire:click="clearAll" class="text-xs text-teal-500 hover:text-teal-600 font-medium">Reset</button>
                            </div>

                            {{-- Employment Type --}}
                            <div class="border-t border-gray-100 pt-4">
                                <button @click="showType = !showType" class="flex items-center justify-between w-full text-xs font-semibold text-gray-700 uppercase tracking-wide mb-3">
                                    Employment Type
                                    <svg :class="showType ? 'rotate-180' : ''" class="w-3.5 h-3.5 text-gray-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                                <div x-show="showType" x-collapse class="space-y-2.5">
                                    @foreach($employmentTypes as $et)
                                    <label class="flex items-center gap-2.5 text-sm text-gray-600 cursor-pointer hover:text-gray-900 transition-colors">
                                        <input type="radio" wire:model.live="type" value="{{ $et->value }}" class="rounded border-gray-300 text-teal-500 focus:ring-teal-500"/>
                                        {{ $et->label() }}
                                    </label>
                                    @endforeach
                                    <label class="flex items-center gap-2.5 text-sm text-gray-400 cursor-pointer hover:text-gray-600 transition-colors">
                                        <input type="radio" wire:model.live="type" value="" class="rounded border-gray-300 text-teal-500 focus:ring-teal-500"/>
                                        All Types
                                    </label>
                                </div>
                            </div>

                            {{-- Location --}}
                            <div class="border-t border-gray-100 pt-4 mt-4" x-data="{ locSearch: '' }">
                                <button @click="showLoc = !showLoc" class="flex items-center justify-between w-full text-xs font-semibold text-gray-700 uppercase tracking-wide mb-3">
                                    Location
                                    <svg :class="showLoc ? 'rotate-180' : ''" class="w-3.5 h-3.5 text-gray-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                                <div x-show="showLoc" x-collapse class="space-y-2">
                                    <div class="relative mb-1">
                                        <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                        <input type="text" x-model="locSearch" placeholder="Search location..." class="w-full pl-8 pr-3 py-1.5 text-xs bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-colors"/>
                                    </div>
                                    @foreach($locations as $idx => $loc)
                                    <button wire:click="setCity('{{ $loc->location_city }}')"
                                        x-show="locSearch === '' ? {{ $idx < 5 ? 'true' : 'false' }} : '{{ strtolower($loc->location_city) }}'.includes(locSearch.toLowerCase())"
                                        class="flex items-center justify-between w-full text-sm px-2 py-1.5 rounded-lg transition-colors {{ $city === $loc->location_city ? 'bg-teal-50 text-teal-700 font-medium' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                        <span class="flex items-center gap-2">
                                            <svg class="w-3.5 h-3.5 {{ $city === $loc->location_city ? 'text-teal-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                            {{ $loc->location_city }}
                                        </span>
                                        <span class="text-[11px] {{ $city === $loc->location_city ? 'text-teal-500' : 'text-gray-400' }}">{{ $loc->jobs_count }}</span>
                                    </button>
                                    @endforeach
                                    @if($city)
                                    <button wire:click="clearFilter('city')" class="w-full text-xs text-gray-400 hover:text-gray-600 pt-1 text-left px-2 transition-colors">Clear location</button>
                                    @endif
                                </div>
                            </div>

                            {{-- Specialty --}}
                            <div class="border-t border-gray-100 pt-4 mt-4">
                                <button @click="showSpec = !showSpec" class="flex items-center justify-between w-full text-xs font-semibold text-gray-700 uppercase tracking-wide mb-3">
                                    Specialty
                                    <svg :class="showSpec ? 'rotate-180' : ''" class="w-3.5 h-3.5 text-gray-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                                <div x-show="showSpec" x-collapse class="flex flex-wrap gap-1.5">
                                    @foreach($specialties as $spec)
                                    <button wire:click="toggleSpecialty({{ $spec->id }})" class="text-xs px-2.5 py-1 rounded-full border transition-colors {{ in_array($spec->id, $specialty) ? 'bg-teal-500 text-white border-teal-500' : 'bg-white text-gray-600 border-gray-200 hover:border-teal-300 hover:text-teal-600' }}">
                                        {{ $spec->name }}
                                    </button>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Experience --}}
                            <div class="border-t border-gray-100 pt-4 mt-4">
                                <button @click="showExp = !showExp" class="flex items-center justify-between w-full text-xs font-semibold text-gray-700 uppercase tracking-wide mb-3">
                                    Experience
                                    <svg :class="showExp ? 'rotate-180' : ''" class="w-3.5 h-3.5 text-gray-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                                <div x-show="showExp" x-collapse class="space-y-2.5">
                                    @foreach(['0-1' => 'Fresher (0-1 yr)', '1-3' => '1-3 years', '3-5' => '3-5 years', '5-10' => '5-10 years', '10+' => '10+ years'] as $val => $label)
                                    <label class="flex items-center gap-2.5 text-sm text-gray-600 cursor-pointer hover:text-gray-900 transition-colors">
                                        <input type="radio" wire:model.live="exp" value="{{ $val }}" class="rounded border-gray-300 text-teal-500 focus:ring-teal-500"/>
                                        {{ $label }}
                                    </label>
                                    @endforeach
                                    <label class="flex items-center gap-2.5 text-sm text-gray-400 cursor-pointer hover:text-gray-600 transition-colors">
                                        <input type="radio" wire:model.live="exp" value="" class="rounded border-gray-300 text-teal-500 focus:ring-teal-500"/>
                                        Any Experience
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </aside>

                {{-- RIGHT: Job results --}}
                <div class="flex-1 min-w-0">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @forelse($jobs as $job)
                            @include('partials.job-card', ['job' => $job, 'featured' => $job->is_featured])
                        @empty
                        <div class="col-span-full bg-white rounded-xl border border-gray-200 py-20 text-center">
                            <svg class="w-14 h-14 mx-auto text-gray-200 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            <p class="text-gray-500 font-medium text-sm">No jobs found matching your criteria.</p>
                            <p class="text-gray-400 text-xs mt-1">Try adjusting your filters or search terms</p>
                            <button wire:click="clearAll" class="mt-4 inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium text-teal-600 bg-teal-50 rounded-lg hover:bg-teal-100 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                Clear all filters
                            </button>
                        </div>
                        @endforelse
                    </div>

                    {{-- Pagination --}}
                    @if($jobs->hasPages())
                    <div class="mt-8">{{ $jobs->links() }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
