<div>
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">Recruiter Plans</h1>
        @if(auth('admin')->user()->hasPermission('plans.manage'))
        <a href="{{ route('admin.plans.recruiter.create') }}" wire:navigate class="h-9 px-4 inline-flex items-center gap-1.5 rounded-lg text-sm font-medium bg-[#464d79] text-white hover:bg-[#464d79]/90 transition-colors">+ New Plan</a>
        @endif
    </div>

    {{-- Tab bar --}}
    <div class="flex gap-1 mb-6 bg-neutral-100 dark:bg-neutral-800 rounded-lg p-1 overflow-x-auto">
        @foreach($tabs as $tab)
        <button wire:click="$set('activeTab', '{{ $tab->value }}')" class="px-4 py-2 rounded-md text-sm font-medium whitespace-nowrap transition-colors {{ $activeTab === $tab->value ? 'bg-white dark:bg-neutral-700 text-[#464d79] shadow-sm' : 'text-neutral-600 hover:text-neutral-900' }}">{{ $tab->label() }}</button>
        @endforeach
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($plans as $plan)
        <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-5">
            <h3 class="text-lg font-bold text-neutral-900 dark:text-white mb-2">{{ $plan->name }}</h3>
            <div class="space-y-1 mb-4">
                @foreach($plan->options as $opt)
                <div class="text-sm text-neutral-600 dark:text-neutral-400">
                    <span class="font-medium">{{ $opt->label }}:</span>
                    ₹{{ number_format($opt->price) }}
                    &middot; {{ $opt->is_unlimited_postings ? 'Unlimited postings' : ($opt->job_postings_per_month . ' postings/mo') }}
                </div>
                @endforeach
            </div>
            <p class="text-xs text-neutral-500 mb-3">{{ $plan->subscriptions()->where('status', 'active')->count() }} active subscribers</p>
            <div class="flex items-center gap-2">
                @if(auth('admin')->user()->hasPermission('plans.manage'))
                <a href="{{ route('admin.plans.recruiter.edit', $plan) }}" wire:navigate class="text-xs font-medium text-[#464d79] hover:underline">Edit</a>
                <button wire:click="toggleActive({{ $plan->id }})" class="text-xs font-medium {{ $plan->is_active ? 'text-amber-600' : 'text-green-600' }}">{{ $plan->is_active ? 'Deactivate' : 'Activate' }}</button>
                @endif
            </div>
        </div>
        @empty
        <p class="text-sm text-neutral-500 col-span-full">No plans for this recruiter type yet.</p>
        @endforelse
    </div>
</div>
