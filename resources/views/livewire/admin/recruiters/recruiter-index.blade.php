<div>
    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">Recruiters</h1>
            <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-1">Manage hospitals, clinics, and medical institutions recruiting on the platform.</p>
        </div>
        <div class="flex items-center gap-3">
            <button wire:click="exportCsv" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-[#464d79] dark:text-[#4ab098] bg-[#464d79]/10 dark:bg-[#4ab098]/10 border border-[#464d79]/20 dark:border-[#4ab098]/20 rounded-lg hover:bg-[#464d79]/20 dark:hover:bg-[#4ab098]/20 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                Export CSV
            </button>
            @if(auth('admin')->user()->hasPermission('recruiters.create'))
            <a href="{{ route('admin.recruiters.create') }}" wire:navigate class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-white bg-[#464d79] hover:bg-[#3a4169] rounded-lg shadow-sm transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/></svg>
                Add Recruiter
            </a>
            @endif
        </div>
    </div>

    {{-- FLASH --}}
    @if(session()->has('message'))
        <div class="mb-4 p-3 rounded-lg bg-[#4ab098]/10 border border-[#4ab098]/20 text-[#4ab098] text-sm font-medium">{{ session('message') }}</div>
    @endif

    {{-- STATS --}}
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
        <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-4">
            <div class="text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">Total</div>
            <div class="text-2xl font-bold text-neutral-900 dark:text-white mt-1">{{ number_format($this->stats['total']) }}</div>
        </div>
        <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-4">
            <div class="text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">Active</div>
            <div class="text-2xl font-bold text-[#4ab098] mt-1">{{ number_format($this->stats['active']) }}</div>
        </div>
        <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-4">
            <div class="text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">Pending</div>
            <div class="text-2xl font-bold text-amber-500 mt-1">{{ number_format($this->stats['pending']) }}</div>
        </div>
        <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-4">
            <div class="text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">Featured</div>
            <div class="text-2xl font-bold text-[#464d79] dark:text-indigo-400 mt-1">{{ number_format($this->stats['featured']) }}</div>
        </div>
        <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-4">
            <div class="text-xs font-medium text-neutral-500 dark:text-neutral-400 uppercase tracking-wider">Blocked</div>
            <div class="text-2xl font-bold text-red-500 mt-1">{{ number_format($this->stats['blocked']) }}</div>
        </div>
    </div>

    {{-- FILTERS --}}
    <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-4 mb-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <div class="sm:col-span-2 lg:col-span-1">
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-neutral-400"><svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/></svg></span>
                    <input wire:model.live.debounce.350ms="search" type="text" placeholder="Search name, email, phone..." class="w-full h-10 pl-9 pr-4 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 rounded-lg text-sm placeholder-neutral-400 focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20"/>
                </div>
            </div>
            <select wire:model.live="status" class="h-10 px-3 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 rounded-lg text-sm text-neutral-700 dark:text-neutral-300 focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20">
                <option value="">All Statuses</option>
                @foreach($statuses as $s)<option value="{{ $s->value }}">{{ $s->label() }}</option>@endforeach
            </select>
            <select wire:model.live="medType" class="h-10 px-3 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 rounded-lg text-sm text-neutral-700 dark:text-neutral-300 focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20">
                <option value="">All Med Types</option>
                @foreach($medTypes as $mt)<option value="{{ $mt->value }}">{{ $mt->label() }}</option>@endforeach
            </select>
            <select wire:model.live="featured" class="h-10 px-3 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 rounded-lg text-sm text-neutral-700 dark:text-neutral-300 focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20">
                <option value="">All Featured</option>
                <option value="yes">Featured</option>
                <option value="no">Not Featured</option>
            </select>
            <select wire:model.live="authProvider" class="h-10 px-3 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 rounded-lg text-sm text-neutral-700 dark:text-neutral-300 focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20">
                <option value="">All Providers</option>
                @foreach($authProviders as $ap)<option value="{{ $ap->value }}">{{ $ap->label() }}</option>@endforeach
            </select>
            <select wire:model.live="profileCompleted" class="h-10 px-3 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 rounded-lg text-sm text-neutral-700 dark:text-neutral-300 focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20">
                <option value="">Profile: All</option>
                <option value="yes">Completed</option>
                <option value="no">Incomplete</option>
            </select>
            <input wire:model.live="dateFrom" type="date" class="h-10 px-3 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 rounded-lg text-sm text-neutral-700 dark:text-neutral-300 focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20"/>
            <input wire:model.live="dateTo" type="date" class="h-10 px-3 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-700 rounded-lg text-sm text-neutral-700 dark:text-neutral-300 focus:outline-none focus:border-[#464d79] focus:ring-2 focus:ring-[#464d79]/20"/>
        </div>
        @if($search || $status || $medType || $featured || $authProvider || $profileCompleted || $dateFrom || $dateTo)
            <div class="mt-3 pt-3 border-t border-neutral-100 dark:border-neutral-700">
                <button wire:click="resetFilters" class="text-sm font-medium text-red-500 hover:text-red-700 inline-flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                    Reset all filters
                </button>
            </div>
        @endif
    </div>

    {{-- TABLE --}}
    <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 overflow-hidden relative">
        <div wire:loading.delay class="absolute inset-0 bg-white/50 dark:bg-neutral-800/50 z-10 flex items-center justify-center">
            <div class="w-6 h-6 border-2 border-[#464d79]/30 border-t-[#464d79] rounded-full animate-spin"></div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-neutral-50 dark:bg-neutral-900/50 border-b border-neutral-200 dark:border-neutral-700">
                    <tr>
                        <th class="px-4 py-3 font-semibold text-neutral-600 dark:text-neutral-300 text-xs uppercase tracking-wider">Institution</th>
                        <th class="px-4 py-3 font-semibold text-neutral-600 dark:text-neutral-300 text-xs uppercase tracking-wider">Contact</th>
                        <th class="px-4 py-3 font-semibold text-neutral-600 dark:text-neutral-300 text-xs uppercase tracking-wider">Email</th>
                        <th class="px-4 py-3 font-semibold text-neutral-600 dark:text-neutral-300 text-xs uppercase tracking-wider">Phone</th>
                        <th class="px-4 py-3 font-semibold text-neutral-600 dark:text-neutral-300 text-xs uppercase tracking-wider">Med Type</th>
                        <th class="px-4 py-3 font-semibold text-neutral-600 dark:text-neutral-300 text-xs uppercase tracking-wider">Featured</th>
                        <th class="px-4 py-3 font-semibold text-neutral-600 dark:text-neutral-300 text-xs uppercase tracking-wider">Provider</th>
                        <th class="px-4 py-3 font-semibold text-neutral-600 dark:text-neutral-300 text-xs uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 font-semibold text-neutral-600 dark:text-neutral-300 text-xs uppercase tracking-wider">Registered</th>
                        <th class="px-4 py-3 font-semibold text-neutral-600 dark:text-neutral-300 text-xs uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100 dark:divide-neutral-700/50">
                    @forelse($recruiters as $rec)
                        @php
                            $profile = $rec->profile;
                            $medTypeColors = [
                                'clinics' => 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-700 dark:text-indigo-400',
                                'small_hospital' => 'bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-400',
                                'larger_hospital' => 'bg-violet-50 dark:bg-violet-900/20 text-violet-700 dark:text-violet-400',
                                'enterprise' => 'bg-[#464d79]/10 text-[#464d79] dark:text-indigo-300',
                                'enterprise_branch' => 'bg-[#4ab098]/10 text-[#4ab098]',
                            ];
                            $statusColors = [
                                'active' => 'bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-400',
                                'inactive' => 'bg-neutral-100 dark:bg-neutral-700 text-neutral-600 dark:text-neutral-400',
                                'blocked' => 'bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400',
                                'pending_verification' => 'bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400',
                            ];
                        @endphp
                        <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-700/30 transition-colors">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-lg bg-[#464d79]/10 flex items-center justify-center text-[#464d79] font-bold text-xs shrink-0">
                                        {{ strtoupper(substr($profile?->institution_name ?? 'R', 0, 2)) }}
                                    </div>
                                    <span class="font-medium text-neutral-900 dark:text-white truncate max-w-[180px]">{{ $profile?->institution_name ?? '—' }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-neutral-600 dark:text-neutral-400">{{ $profile?->contact_person_name ?? '—' }}</td>
                            <td class="px-4 py-3 text-neutral-600 dark:text-neutral-400">{{ $rec->email ?? '—' }}</td>
                            <td class="px-4 py-3 text-neutral-600 dark:text-neutral-400 whitespace-nowrap">{{ $rec->phone ?? '—' }}</td>
                            <td class="px-4 py-3">
                                @if($profile?->med_type)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $medTypeColors[$profile->med_type->value] ?? '' }}">{{ $profile->med_type->label() }}</span>
                                @else
                                    <span class="text-neutral-400">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if(auth('admin')->user()->hasPermission('recruiters.edit'))
                                <button wire:click="toggleFeatured('{{ $rec->id }}')" wire:confirm="Toggle featured status?" class="text-neutral-400 hover:text-amber-500 transition-colors">
                                @else
                                <span class="text-neutral-400">
                                @endif
                                    @if($profile?->is_featured)
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-amber-500" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                                    @endif
                                @if(auth('admin')->user()->hasPermission('recruiters.edit'))
                                </button>
                                @else
                                </span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if($rec->auth_provider)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-neutral-100 dark:bg-neutral-700 text-neutral-700 dark:text-neutral-300">{{ $rec->auth_provider->label() }}</span>
                                @else
                                    <span class="text-neutral-400">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $statusColors[$rec->status->value] ?? '' }}">{{ $rec->status->label() }}</span>
                            </td>
                            <td class="px-4 py-3 text-neutral-500 text-xs whitespace-nowrap">{{ $rec->created_at->format('d M Y') }}</td>
                            <td class="px-4 py-3 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('admin.recruiters.show', $rec) }}" wire:navigate title="View" class="w-7 h-7 inline-flex items-center justify-center rounded text-neutral-400 hover:text-[#464d79] hover:bg-[#464d79]/10 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/></svg>
                                    </a>
                                    @if(auth('admin')->user()->hasPermission('recruiters.edit'))
                                    <a href="{{ route('admin.recruiters.edit', $rec) }}" wire:navigate title="Edit" class="w-7 h-7 inline-flex items-center justify-center rounded text-neutral-400 hover:text-[#464d79] hover:bg-[#464d79]/10 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/></svg>
                                    </a>
                                    @endif
                                    @if(auth('admin')->user()->hasPermission('recruiters.edit') && $rec->status === \App\Enums\UserStatusEnum::Active)
                                        <button wire:click="changeStatus('{{ $rec->id }}', 'blocked')" wire:confirm="Block this recruiter?" title="Block" class="w-7 h-7 inline-flex items-center justify-center rounded text-neutral-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd"/></svg>
                                        </button>
                                    @elseif(auth('admin')->user()->hasPermission('recruiters.edit'))
                                        <button wire:click="changeStatus('{{ $rec->id }}', 'active')" wire:confirm="Activate this recruiter?" title="Activate" class="w-7 h-7 inline-flex items-center justify-center rounded text-neutral-400 hover:text-[#4ab098] hover:bg-[#4ab098]/10 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
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
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-neutral-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd"/></svg>
                                    </div>
                                    <h3 class="text-sm font-semibold text-neutral-700 dark:text-neutral-300 mb-1">No recruiters found</h3>
                                    <p class="text-xs text-neutral-500 mb-4">{{ ($search || $status || $medType) ? 'Try adjusting your filters.' : 'Get started by adding your first recruiter.' }}</p>
                                    @if(!$search && !$status && !$medType)
                                        <a href="{{ route('admin.recruiters.create') }}" wire:navigate class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-[#464d79] rounded-lg shadow-sm">Add Recruiter</a>
                                    @else
                                        <button wire:click="resetFilters" class="text-sm font-medium text-[#464d79] dark:text-[#4ab098] hover:underline">Clear filters</button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($recruiters->hasPages())
            <div class="px-4 py-3 border-t border-neutral-200 dark:border-neutral-700">{{ $recruiters->links() }}</div>
        @endif
    </div>
</div>
