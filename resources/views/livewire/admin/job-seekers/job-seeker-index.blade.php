<div>
    {{-- PAGE HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">Job Seekers</h1>
            <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-1">Manage healthcare professionals and support staff registered on the platform.</p>
        </div>
        <div class="flex items-center gap-3">
            @if(auth('admin')->user()->hasPermission('job_seekers.create'))
            <a href="{{ route('admin.job-seekers.bulk-import') }}" wire:navigate class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-[#464d79] dark:text-[#4ab098] bg-[#464d79]/10 dark:bg-[#4ab098]/10 border border-[#464d79]/20 dark:border-[#4ab098]/20 rounded-lg hover:bg-[#464d79]/20 dark:hover:bg-[#4ab098]/20 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                </svg>
                Bulk Import
            </a>
            @endif
            <button wire:click="exportCsv" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-[#464d79] dark:text-[#4ab098] bg-[#464d79]/10 dark:bg-[#4ab098]/10 border border-[#464d79]/20 dark:border-[#4ab098]/20 rounded-lg hover:bg-[#464d79]/20 dark:hover:bg-[#4ab098]/20 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
                Export CSV
            </button>
            @if(auth('admin')->user()->hasPermission('job_seekers.create'))
            <a href="{{ route('admin.job-seekers.create') }}" wire:navigate class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-white bg-[#464d79] hover:bg-[#3a4169] rounded-lg shadow-sm transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                </svg>
                Add Job Seeker
            </a>
            @endif
        </div>
    </div>

    {{-- FLASH MESSAGE --}}
    @if(session()->has('message'))
        <div class="mb-4 p-3 rounded-lg bg-[#4ab098]/10 border border-[#4ab098]/20 text-[#4ab098] text-sm font-medium">
            {{ session('message') }}
        </div>
    @endif
    @if(session()->has('success'))
        <div class="mb-4 p-3 rounded-lg bg-[#4ab098]/10 border border-[#4ab098]/20 text-[#4ab098] text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif

    {{-- STATS STRIP --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-4">
            <div class="text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">Total Job Seekers</div>
            <div class="text-2xl font-bold text-neutral-900 dark:text-white mt-1">{{ number_format($this->stats['total']) }}</div>
        </div>
        <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-4">
            <div class="text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">Active</div>
            <div class="text-2xl font-bold text-[#4ab098] mt-1">{{ number_format($this->stats['active']) }}</div>
        </div>
        <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-4">
            <div class="text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">Pending Verification</div>
            <div class="text-2xl font-bold text-amber-500 mt-1">{{ number_format($this->stats['pending']) }}</div>
        </div>
        <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-4">
            <div class="text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">Blocked</div>
            <div class="text-2xl font-bold text-red-500 mt-1">{{ number_format($this->stats['blocked']) }}</div>
        </div>
    </div>

    {{-- FILTERS --}}
    <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-4 mb-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            {{-- Search --}}
            <div class="sm:col-span-2 lg:col-span-1">
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-neutral-400 pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                        </svg>
                    </span>
                    <input
                        wire:model.live.debounce.300ms="search"
                        type="text"
                        placeholder="Search name, email, phone..."
                        class="w-full h-10 pl-9 pr-4 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 rounded-lg text-sm placeholder-neutral-400 focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20 transition-all"
                    />
                </div>
            </div>

            {{-- Status --}}
            <select wire:model.live="status" class="h-10 px-3 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 rounded-lg text-sm text-neutral-700 dark:text-neutral-300 focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20 transition-all">
                <option value="">All Statuses</option>
                @foreach($statuses as $s)
                    <option value="{{ $s->value }}">{{ $s->label() }}</option>
                @endforeach
            </select>

            {{-- Category --}}
            <select wire:model.live="category" class="h-10 px-3 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 rounded-lg text-sm text-neutral-700 dark:text-neutral-300 focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20 transition-all">
                <option value="">All Categories</option>
                @foreach($this->categories as $cat)
                    <option value="{{ $cat->slug }}">{{ $cat->name }}</option>
                @endforeach
            </select>

            {{-- Sub-category --}}
            <select wire:model.live="subcategory" class="h-10 px-3 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 rounded-lg text-sm text-neutral-700 dark:text-neutral-300 focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20 transition-all">
                <option value="">All Sub-categories</option>
                @foreach($this->subcategories as $sub)
                    <option value="{{ $sub->name }}">{{ $sub->name }}</option>
                @endforeach
            </select>

            {{-- Auth Provider --}}
            <select wire:model.live="authProvider" class="h-10 px-3 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 rounded-lg text-sm text-neutral-700 dark:text-neutral-300 focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20 transition-all">
                <option value="">All Providers</option>
                @foreach($authProviders as $ap)
                    <option value="{{ $ap->value }}">{{ $ap->label() }}</option>
                @endforeach
            </select>

            {{-- Profile Completed --}}
            <select wire:model.live="profileCompleted" class="h-10 px-3 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 rounded-lg text-sm text-neutral-700 dark:text-neutral-300 focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20 transition-all">
                <option value="">Profile: All</option>
                <option value="yes">Completed</option>
                <option value="no">Incomplete</option>
            </select>

            {{-- Date From --}}
            <input
                wire:model.live="dateFrom"
                type="date"
                class="h-10 px-3 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 rounded-lg text-sm text-neutral-700 dark:text-neutral-300 focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20 transition-all"
                placeholder="From date"
            />

            {{-- Date To --}}
            <input
                wire:model.live="dateTo"
                type="date"
                class="h-10 px-3 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 rounded-lg text-sm text-neutral-700 dark:text-neutral-300 focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20 transition-all"
                placeholder="To date"
            />
        </div>

        {{-- Reset --}}
        @if($search || $status || $category || $subcategory || $authProvider || $profileCompleted || $dateFrom || $dateTo)
            <div class="mt-3 pt-3 border-t border-neutral-100 dark:border-neutral-700">
                <button wire:click="resetFilters" class="text-sm font-medium text-red-500 hover:text-red-700 dark:hover:text-red-400 transition-colors inline-flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                    Reset all filters
                </button>
            </div>
        @endif
    </div>

    {{-- TABLE --}}
    <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 overflow-hidden">
        {{-- Loading overlay --}}
        <div wire:loading.delay class="absolute inset-0 bg-white/50 dark:bg-neutral-800/50 z-10 flex items-center justify-center">
            <div class="w-6 h-6 border-2 border-[#464d79]/30 border-t-[#464d79] rounded-full animate-spin"></div>
        </div>

        <div class="overflow-x-auto relative">
            <table class="w-full text-sm text-left">
                <thead class="bg-neutral-50 dark:bg-neutral-900/50 border-b border-neutral-200 dark:border-neutral-700">
                    <tr>
                        <th class="px-4 py-3 font-semibold text-neutral-600 dark:text-neutral-300 text-xs uppercase tracking-wider">Name</th>
                        <th class="px-4 py-3 font-semibold text-neutral-600 dark:text-neutral-300 text-xs uppercase tracking-wider">Email</th>
                        <th class="px-4 py-3 font-semibold text-neutral-600 dark:text-neutral-300 text-xs uppercase tracking-wider">Phone</th>
                        <th class="px-4 py-3 font-semibold text-neutral-600 dark:text-neutral-300 text-xs uppercase tracking-wider">Category</th>
                        <th class="px-4 py-3 font-semibold text-neutral-600 dark:text-neutral-300 text-xs uppercase tracking-wider">Sub-category</th>
                        <th class="px-4 py-3 font-semibold text-neutral-600 dark:text-neutral-300 text-xs uppercase tracking-wider">Provider</th>
                        <th class="px-4 py-3 font-semibold text-neutral-600 dark:text-neutral-300 text-xs uppercase tracking-wider">Profile</th>
                        <th class="px-4 py-3 font-semibold text-neutral-600 dark:text-neutral-300 text-xs uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 font-semibold text-neutral-600 dark:text-neutral-300 text-xs uppercase tracking-wider">Registered</th>
                        <th class="px-4 py-3 font-semibold text-neutral-600 dark:text-neutral-300 text-xs uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100 dark:divide-neutral-700/50">
                    @forelse($jobSeekers as $seeker)
                        <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-700/30 transition-colors">
                            <td class="px-4 py-3 font-medium text-neutral-900 dark:text-white whitespace-nowrap">{{ $seeker->jobSeekerProfile?->getFullName() ?? $seeker->name }}</td>
                            <td class="px-4 py-3 text-neutral-600 dark:text-neutral-400">{{ $seeker->email ?? '—' }}</td>
                            <td class="px-4 py-3 text-neutral-600 dark:text-neutral-400 whitespace-nowrap">{{ $seeker->phone }}</td>
                            <td class="px-4 py-3 text-neutral-600 dark:text-neutral-400">{{ $seeker->jobSeekerProfile?->category_name ?? '—' }}</td>
                            <td class="px-4 py-3 text-neutral-600 dark:text-neutral-400">{{ $seeker->jobSeekerProfile?->subcategory_name ?? '—' }}</td>
                            <td class="px-4 py-3">
                                @if($seeker->auth_provider)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-neutral-100 dark:bg-neutral-700 text-neutral-700 dark:text-neutral-300">
                                        {{ $seeker->auth_provider->label() }}
                                    </span>
                                @else
                                    <span class="text-neutral-400">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if($seeker->is_profile_completed)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs font-medium bg-[#4ab098]/10 text-[#4ab098]">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                        Complete
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400">Incomplete</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @php
                                    $statusColors = [
                                        'active' => 'bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-400',
                                        'inactive' => 'bg-neutral-100 dark:bg-neutral-700 text-neutral-600 dark:text-neutral-400',
                                        'blocked' => 'bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400',
                                        'pending_verification' => 'bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400',
                                    ];
                                    $color = $statusColors[$seeker->status->value] ?? $statusColors['inactive'];
                                @endphp
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $color }}">
                                    {{ $seeker->status->label() }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-neutral-500 dark:text-neutral-400 whitespace-nowrap text-xs">{{ $seeker->created_at->format('d M Y') }}</td>
                            <td class="px-4 py-3 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('admin.job-seekers.show', $seeker) }}" wire:navigate title="View" class="w-7 h-7 inline-flex items-center justify-center rounded text-neutral-400 hover:text-[#464d79] hover:bg-[#464d79]/10 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                        </svg>
                                    </a>
                                    @if(auth('admin')->user()->hasPermission('job_seekers.edit'))
                                    <a href="{{ route('admin.job-seekers.edit', $seeker) }}" wire:navigate title="Edit" class="w-7 h-7 inline-flex items-center justify-center rounded text-neutral-400 hover:text-[#464d79] hover:bg-[#464d79]/10 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                        </svg>
                                    </a>
                                    @endif
                                    @if(auth('admin')->user()->hasPermission('job_seekers.edit') && $seeker->status === \App\Enums\UserStatusEnum::Active)
                                        <button wire:click="toggleStatus('{{ $seeker->id }}', 'blocked')" wire:confirm="Block this user?" title="Block" class="w-7 h-7 inline-flex items-center justify-center rounded text-neutral-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd"/>
                                            </svg>
                                        </button>
                                    @elseif($seeker->status === \App\Enums\UserStatusEnum::Blocked || $seeker->status === \App\Enums\UserStatusEnum::Inactive)
                                        <button wire:click="toggleStatus('{{ $seeker->id }}', 'active')" wire:confirm="Activate this user?" title="Activate" class="w-7 h-7 inline-flex items-center justify-center rounded text-neutral-400 hover:text-[#4ab098] hover:bg-[#4ab098]/10 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                        </button>
                                    @elseif($seeker->status === \App\Enums\UserStatusEnum::PendingVerification)
                                        <button wire:click="toggleStatus('{{ $seeker->id }}', 'active')" wire:confirm="Verify and activate this user?" title="Approve" class="w-7 h-7 inline-flex items-center justify-center rounded text-neutral-400 hover:text-[#4ab098] hover:bg-[#4ab098]/10 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-4 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 rounded-full bg-neutral-100 dark:bg-neutral-700 flex items-center justify-center mb-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-neutral-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                                        </svg>
                                    </div>
                                    <h3 class="text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-1">No job seekers found</h3>
                                    <p class="text-xs text-neutral-500 dark:text-neutral-400 mb-4">
                                        @if($search || $status || $category || $subcategory || $authProvider || $profileCompleted || $dateFrom || $dateTo)
                                            Try adjusting your filters or search term.
                                        @else
                                            Get started by adding your first job seeker.
                                        @endif
                                    </p>
                                    @if($search || $status || $category || $subcategory || $authProvider || $profileCompleted || $dateFrom || $dateTo)
                                        <button wire:click="resetFilters" class="text-sm font-medium text-[#464d79] dark:text-[#4ab098] hover:underline">Clear filters</button>
                                    @else
                                        <a href="#" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-[#464d79] hover:bg-[#3a4169] rounded-lg shadow-sm transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                                            </svg>
                                            Add Job Seeker
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        @if($jobSeekers->hasPages())
            <div class="px-4 py-3 border-t border-neutral-200 dark:border-neutral-700">
                {{ $jobSeekers->links() }}
            </div>
        @endif
    </div>
</div>
