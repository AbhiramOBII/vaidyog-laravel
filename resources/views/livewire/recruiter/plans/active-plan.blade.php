<div class="max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold text-neutral-900 dark:text-white mb-6">My Plan</h1>

    <div class="bg-white dark:bg-neutral-800 rounded-2xl border border-neutral-200 dark:border-neutral-700 p-6 mb-6">
        @if($subscription)
        <div class="flex items-center gap-3 mb-4">
            <h2 class="text-xl font-bold">{{ $subscription->plan_name }}</h2>
            @if($subscription->option)<span class="text-xs bg-neutral-100 px-2 py-0.5 rounded-full">{{ $subscription->option->label }}</span>@endif
        </div>
        <div class="mb-4">
            <div class="flex justify-between text-sm mb-1">
                <span class="text-neutral-600">Job postings this month</span>
                <span class="font-medium">{{ $remaining === 'unlimited' ? 'Unlimited' : $remaining . ' remaining' }}</span>
            </div>
            @if($remaining !== 'unlimited' && $subscription->job_postings_per_month)
            @php $used = $subscription->job_postings_per_month - $remaining; $pct = ($used / $subscription->job_postings_per_month) * 100; @endphp
            <div class="w-full h-2 bg-neutral-100 rounded-full overflow-hidden">
                <div class="h-full rounded-full {{ $pct > 80 ? 'bg-red-500' : ($pct > 50 ? 'bg-amber-500' : 'bg-[#4ab098]') }}" style="width: {{ min($pct, 100) }}%"></div>
            </div>
            @endif
        </div>
        <p class="text-sm text-neutral-500">Expires: {{ $subscription->expires_at?->format('M j, Y') ?? 'Never' }}</p>
        @else
        <p class="text-neutral-500">No active subscription. <a href="{{ route('recruiter.plans.index') }}" class="text-[#464d79] font-medium hover:underline">View plans</a></p>
        @endif
    </div>

    @if($payments->isNotEmpty())
    <div class="bg-white dark:bg-neutral-800 rounded-2xl border border-neutral-200 dark:border-neutral-700 overflow-hidden">
        <div class="px-6 py-4 border-b border-neutral-100"><h3 class="text-sm font-semibold text-neutral-500 uppercase">Recent Payments</h3></div>
        <table class="w-full text-sm">
            <tbody class="divide-y divide-neutral-100">
                @foreach($payments as $p)
                <tr><td class="px-6 py-3 font-medium">₹{{ number_format($p->amount) }}</td><td class="px-6 py-3"><span class="text-xs px-2 py-0.5 rounded-full {{ $p->status->getBadgeClasses() }}">{{ $p->status->label() }}</span></td><td class="px-6 py-3 text-neutral-500">{{ $p->paid_at?->format('M j, Y') ?? '-' }}</td></tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
