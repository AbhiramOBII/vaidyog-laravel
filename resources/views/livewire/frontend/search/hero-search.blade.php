<div>
    <form wire:submit="search" class="bg-white rounded-xl shadow-xl p-4 md:p-6">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
            {{-- Keywords --}}
            <div class="md:col-span-5 relative">
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" wire:model.live.debounce.300ms="query" placeholder="Job title, skills, designation" class="w-full pl-10 pr-4 py-3 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500" autocomplete="off"/>
                </div>
                @if($showQueryDropdown)
                <div class="absolute z-20 top-full left-0 right-0 mt-1 bg-white border border-gray-200 rounded-lg shadow-lg overflow-hidden">
                    @foreach($querySuggestions as $suggestion)
                    <button type="button" wire:click="selectQuery('{{ $suggestion }}')" class="w-full px-4 py-2.5 text-left text-sm text-gray-700 hover:bg-primary-50 flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        {{ $suggestion }}
                    </button>
                    @endforeach
                </div>
                @endif
            </div>

            {{-- Location --}}
            <div class="md:col-span-3 relative">
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <input type="text" wire:model.live.debounce.300ms="location" placeholder="City, State or Remote" class="w-full pl-10 pr-4 py-3 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500" autocomplete="off"/>
                </div>
                @if($showLocationDropdown)
                <div class="absolute z-20 top-full left-0 right-0 mt-1 bg-white border border-gray-200 rounded-lg shadow-lg overflow-hidden">
                    @foreach($locationSuggestions as $city)
                    <button type="button" wire:click="selectLocation('{{ $city }}')" class="w-full px-4 py-2.5 text-left text-sm text-gray-700 hover:bg-primary-50 flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                        {{ $city }}
                    </button>
                    @endforeach
                </div>
                @endif
            </div>

            {{-- Experience --}}
            <div class="md:col-span-2">
                <select wire:model="experience" class="w-full px-4 py-3 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 text-gray-600">
                    <option value="">Experience</option>
                    <option value="0-1">Fresher (0-1 yr)</option>
                    <option value="1-3">1-3 years</option>
                    <option value="3-5">3-5 years</option>
                    <option value="5-10">5-10 years</option>
                    <option value="10+">10+ years</option>
                </select>
            </div>

            {{-- Search button --}}
            <div class="md:col-span-2">
                <button type="submit" class="w-full py-3 px-5 bg-teal-300 hover:bg-teal-400 text-white text-sm font-semibold rounded-lg transition-colors whitespace-nowrap">
                    Search Jobs →
                </button>
            </div>
        </div>
    </form>
</div>
