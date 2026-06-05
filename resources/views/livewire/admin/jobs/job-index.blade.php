<div>
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">Job Postings</h1>
        @if(auth('admin')->user()->hasPermission('jobs.create'))
        <a href="{{ route('admin.jobs.create') }}" wire:navigate class="inline-flex items-center gap-2 h-10 px-5 bg-[#464d79] hover:bg-[#3a4169] text-white font-semibold rounded-lg text-sm transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/></svg>
            Post New Job
        </a>
        @endif
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 sm:grid-cols-5 gap-4 mb-6">
        <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-4 text-center"><div class="text-2xl font-bold text-neutral-900 dark:text-white">{{ $this->stats['total'] }}</div><div class="text-xs text-neutral-500 mt-1">Total</div></div>
        <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-4 text-center"><div class="text-2xl font-bold text-green-600">{{ $this->stats['live'] }}</div><div class="text-xs text-neutral-500 mt-1">Live</div></div>
        <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-4 text-center"><div class="text-2xl font-bold text-amber-500">{{ $this->stats['pending'] }}</div><div class="text-xs text-neutral-500 mt-1">Pending</div></div>
        <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-4 text-center"><div class="text-2xl font-bold text-[#4ab098]">{{ $this->stats['featured'] }}</div><div class="text-xs text-neutral-500 mt-1">Featured</div></div>
        <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-4 text-center"><div class="text-2xl font-bold text-neutral-400">{{ $this->stats['expired'] }}</div><div class="text-xs text-neutral-500 mt-1">Expired</div></div>
    </div>

    {{-- Filters --}}
    <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-4 mb-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search title or institution..." class="h-10 px-4 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-600 rounded-lg text-sm focus:outline-none focus:border-[#464d79]"/>
            <select wire:model.live="approvalStatus" class="h-10 px-4 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-600 rounded-lg text-sm">
                <option value="">All Approval</option>
                <option value="approved">Approved</option>
                <option value="pending">Pending</option>
                <option value="rejected">Rejected</option>
            </select>
            <select wire:model.live="featured" class="h-10 px-4 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-600 rounded-lg text-sm">
                <option value="">All Featured</option>
                <option value="yes">Featured</option>
                <option value="no">Not Featured</option>
            </select>
            <select wire:model.live="employmentType" class="h-10 px-4 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-600 rounded-lg text-sm">
                <option value="">All Types</option>
                @foreach($employmentTypes as $et)<option value="{{ $et->value }}">{{ $et->label() }}</option>@endforeach
            </select>
            <select wire:model.live="specialtyId" class="h-10 px-4 bg-neutral-50 dark:bg-neutral-900 border border-neutral-200 dark:border-neutral-600 rounded-lg text-sm">
                <option value="">All Specialties</option>
                @foreach($specialties as $spec)<option value="{{ $spec->id }}">{{ $spec->name }}</option>@endforeach
            </select>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-neutral-50 dark:bg-neutral-900 border-b border-neutral-200 dark:border-neutral-700">
                    <tr>
                        <th class="text-left px-4 py-3 font-medium text-neutral-600 dark:text-neutral-400">Job Title</th>
                        <th class="text-left px-4 py-3 font-medium text-neutral-600 dark:text-neutral-400">Institution</th>
                        <th class="text-left px-4 py-3 font-medium text-neutral-600 dark:text-neutral-400">Type</th>
                        <th class="text-center px-4 py-3 font-medium text-neutral-600 dark:text-neutral-400">Vacancies</th>
                        <th class="text-left px-4 py-3 font-medium text-neutral-600 dark:text-neutral-400">Status</th>
                        <th class="text-center px-4 py-3 font-medium text-neutral-600 dark:text-neutral-400">Featured</th>
                        <th class="text-left px-4 py-3 font-medium text-neutral-600 dark:text-neutral-400">Posted</th>
                        <th class="text-right px-4 py-3 font-medium text-neutral-600 dark:text-neutral-400">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100 dark:divide-neutral-700">
                    @forelse($jobs as $job)
                    <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-900/50">
                        <td class="px-4 py-3"><a href="{{ route('admin.jobs.show', $job) }}" wire:navigate class="font-medium text-neutral-900 dark:text-white hover:text-[#464d79]">{{ Str::limit($job->job_title, 35) }}</a></td>
                        <td class="px-4 py-3 text-neutral-600 dark:text-neutral-400 text-xs">{{ Str::limit($job->institution_name, 25) }}</td>
                        <td class="px-4 py-3"><span class="text-xs px-2 py-0.5 rounded-full bg-neutral-100 dark:bg-neutral-700">{{ $job->employment_type->label() }}</span></td>
                        <td class="px-4 py-3 text-center">{{ $job->number_of_vacancies }}</td>
                        <td class="px-4 py-3">
                            @php $st = $job->getDisplayStatus(); $color = $job->getDisplayStatusColor(); @endphp
                            <span class="text-xs px-2 py-0.5 rounded-full font-medium @if($color==='green') bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 @elseif($color==='amber') bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400 @elseif($color==='red') bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400 @else bg-neutral-100 text-neutral-600 dark:bg-neutral-700 dark:text-neutral-300 @endif">{{ $st }}</span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if(auth('admin')->user()->hasPermission('jobs.edit'))
                            <button wire:click="toggleFeatured('{{ $job->id }}')" class="text-lg {{ $job->is_featured ? 'text-amber-500' : 'text-neutral-300 hover:text-amber-400' }} transition-colors" title="Toggle featured">&#9733;</button>
                            @else
                            <span class="text-lg {{ $job->is_featured ? 'text-amber-500' : 'text-neutral-300' }}">&#9733;</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-neutral-500 text-xs">{{ $job->created_at->format('M d, Y') }}</td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('admin.jobs.show', $job) }}" wire:navigate class="p-1.5 rounded hover:bg-neutral-100 dark:hover:bg-neutral-700 text-neutral-500" title="View">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/></svg>
                                </a>
                                @if(auth('admin')->user()->hasPermission('jobs.edit'))
                                <a href="{{ route('admin.jobs.edit', $job) }}" wire:navigate class="p-1.5 rounded hover:bg-neutral-100 dark:hover:bg-neutral-700 text-neutral-500" title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/></svg>
                                </a>
                                @endif
                                @if(auth('admin')->user()->hasPermission('jobs.approve') && !$job->admin_approved && !$job->rejection_reason)
                                <button wire:click="approve('{{ $job->id }}')" class="p-1.5 rounded hover:bg-green-50 text-green-600" title="Approve">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                </button>
                                @endif
                                @if(auth('admin')->user()->hasPermission('jobs.edit'))
                                <button wire:click="toggleActive('{{ $job->id }}')" class="p-1.5 rounded hover:bg-neutral-100 dark:hover:bg-neutral-700 {{ $job->is_active ? 'text-green-500' : 'text-neutral-400' }}" title="{{ $job->is_active ? 'Disable' : 'Enable' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8 7a1 1 0 00-1 1v4a1 1 0 001 1h4a1 1 0 001-1V8a1 1 0 00-1-1H8z" clip-rule="evenodd"/></svg>
                                </button>
                                @endif
                                @if(auth('admin')->user()->hasPermission('jobs.delete'))
                                <button wire:click="moveToBin('{{ $job->id }}')" wire:confirm="Move to bin?" class="p-1.5 rounded hover:bg-red-50 text-neutral-500 hover:text-red-500" title="Bin">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="px-4 py-12 text-center text-neutral-500">No job postings found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($jobs->hasPages())
        <div class="px-4 py-3 border-t border-neutral-200 dark:border-neutral-700">{{ $jobs->links() }}</div>
        @endif
    </div>
</div>
