<div>
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">Job Seeker Plans</h1>
        <a href="{{ route('admin.plans.jobseeker.create') }}" wire:navigate class="h-9 px-4 inline-flex items-center gap-1.5 rounded-lg text-sm font-medium bg-[#464d79] text-white hover:bg-[#464d79]/90 transition-colors">+ New Plan</a>
    </div>

    @if(session('success'))
    <div class="mb-4 p-3 rounded-lg bg-green-50 text-green-700 text-sm">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach($plans as $plan)
        <div class="bg-white dark:bg-neutral-800 rounded-xl border {{ $plan->is_recommended ? 'border-[#4ab098] ring-2 ring-[#4ab098]/20' : 'border-neutral-200 dark:border-neutral-700' }} p-5 relative">
            @if($plan->is_recommended)
            <span class="absolute -top-2.5 left-1/2 -translate-x-1/2 text-xs bg-[#4ab098] text-white px-3 py-0.5 rounded-full font-medium">Most Popular</span>
            @endif
            <div class="flex items-center gap-2 mb-3">
                <h3 class="text-lg font-bold text-neutral-900 dark:text-white">{{ $plan->name }}</h3>
                <span class="text-xs px-2 py-0.5 rounded-full {{ $plan->ranking->getBadgeClasses() }}">{{ $plan->ranking->getLabel() }}</span>
            </div>
            <div class="space-y-1 mb-4">
                @foreach($plan->options as $opt)
                <div class="text-sm text-neutral-600 dark:text-neutral-400">
                    <span class="font-medium">{{ $opt->label }}:</span>
                    @if($opt->price == 0) Free @else ₹{{ number_format($opt->price) }} @endif
                    &middot; {{ $opt->is_unlimited ? 'Unlimited' : ($opt->applications_per_month . '/mo') }}
                </div>
                @endforeach
            </div>
            <p class="text-xs text-neutral-500 mb-4">{{ $plan->subscriptions()->where('status', 'active')->count() }} active subscribers</p>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.plans.jobseeker.edit', $plan) }}" wire:navigate class="text-xs font-medium text-[#464d79] hover:underline">Edit</a>
                <button wire:click="toggleActive({{ $plan->id }})" class="text-xs font-medium {{ $plan->is_active ? 'text-amber-600' : 'text-green-600' }}">{{ $plan->is_active ? 'Deactivate' : 'Activate' }}</button>
            </div>
        </div>
        @endforeach
    </div>
</div>
