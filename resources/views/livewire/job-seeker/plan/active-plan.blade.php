<div class="max-w-2xl mx-auto py-10 px-4">
    <h1 class="text-2xl font-bold text-neutral-900 dark:text-white mb-6">My Plan</h1>

    <div class="bg-white dark:bg-neutral-800 rounded-2xl border border-neutral-200 dark:border-neutral-700 p-6 mb-6">
        @if($subscription)
        <div class="flex items-center gap-3 mb-4">
            <h2 class="text-xl font-bold text-neutral-900 dark:text-white">{{ $subscription->plan_name }}</h2>
            <span class="text-xs px-2 py-0.5 rounded-full {{ $subscription->ranking->getBadgeClasses() }}">{{ $subscription->ranking->getLabel() }}</span>
            @if($subscription->option)
            <span class="text-xs text-neutral-500 bg-neutral-100 px-2 py-0.5 rounded-full">{{ $subscription->option->label }}</span>
            @endif
        </div>

        {{-- Usage --}}
        <div class="mb-4">
            <div class="flex justify-between text-sm mb-1">
                <span class="text-neutral-600 dark:text-neutral-400">Applications this month</span>
                <span class="font-medium">
                    @if($remaining === 'unlimited')
                    Unlimited
                    @else
                    {{ $remaining }} remaining
                    @endif
                </span>
            </div>
            @if($remaining !== 'unlimited' && $subscription->applications_per_month)
            @php $used = $subscription->applications_per_month - $remaining; $pct = ($used / $subscription->applications_per_month) * 100; @endphp
            <div class="w-full h-2 bg-neutral-100 dark:bg-neutral-700 rounded-full overflow-hidden">
                <div class="h-full rounded-full transition-all {{ $pct > 80 ? 'bg-red-500' : ($pct > 50 ? 'bg-amber-500' : 'bg-[#4ab098]') }}" style="width: {{ min($pct, 100) }}%"></div>
            </div>
            @endif
        </div>

        <div class="text-sm text-neutral-600 dark:text-neutral-400">
            @if($subscription->expires_at)
            <p>Expires: <span class="font-medium text-neutral-900 dark:text-white">{{ $subscription->expires_at->format('M j, Y') }}</span></p>
            @else
            <p>Expires: <span class="font-medium text-neutral-900 dark:text-white">Never</span></p>
            @endif
        </div>
        @else
        <div class="text-center py-4">
            <h2 class="text-lg font-bold text-neutral-900 dark:text-white">Basic (Free)</h2>
            <p class="text-sm text-neutral-500 mt-1">5 applications per month &middot; Ranking D</p>
            <p class="text-sm text-neutral-600 mt-2">{{ $remaining }} applications remaining this month</p>
        </div>
        @endif

        <div class="mt-4 pt-4 border-t border-neutral-100 dark:border-neutral-700">
            <a href="{{ route('plans.index') }}" class="text-sm font-semibold text-[#464d79] hover:underline">View all plans &rarr;</a>
        </div>
    </div>

    {{-- Payment History --}}
    @if($payments->isNotEmpty())
    <div class="bg-white dark:bg-neutral-800 rounded-2xl border border-neutral-200 dark:border-neutral-700 overflow-hidden">
        <div class="px-6 py-4 border-b border-neutral-100 dark:border-neutral-700">
            <h3 class="text-sm font-semibold text-neutral-500 uppercase">Recent Payments</h3>
        </div>
        <table class="w-full text-sm">
            <tbody class="divide-y divide-neutral-100 dark:divide-neutral-700">
                @foreach($payments as $payment)
                <tr>
                    <td class="px-6 py-3 font-medium">₹{{ number_format($payment->amount) }}</td>
                    <td class="px-6 py-3"><span class="text-xs px-2 py-0.5 rounded-full {{ $payment->status->getBadgeClasses() }}">{{ $payment->status->label() }}</span></td>
                    <td class="px-6 py-3 text-neutral-500">{{ $payment->paid_at?->format('M j, Y') ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
