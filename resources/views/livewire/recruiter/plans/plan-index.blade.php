<div class="max-w-5xl">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-neutral-900 dark:text-white">Subscription & Billing</h1>
        <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-1">Manage your subscription plan and view billing history.</p>
    </div>

    {{-- Current Plan --}}
    <div class="bg-white dark:bg-neutral-900 rounded-xl border border-neutral-200 dark:border-neutral-800 p-6 mb-8">
        <div class="flex items-start justify-between flex-wrap gap-4">
            <div>
                <h2 class="text-sm font-semibold text-neutral-500 dark:text-neutral-400 uppercase tracking-wide mb-2">Current Plan</h2>
                @if ($activeSub)
                    <div class="flex items-center gap-3 mb-2">
                        <h3 class="text-xl font-bold text-neutral-900 dark:text-white">{{ $activeSub->plan_name }}</h3>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700 dark:bg-green-950/40 dark:text-green-400">Active</span>
                    </div>
                    <div class="flex items-center gap-4 text-sm text-neutral-600 dark:text-neutral-400">
                        <span class="flex items-center gap-1.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-neutral-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/></svg>
                            Expires: {{ $activeSub->expires_at?->format('M j, Y') ?? 'Never (Lifetime)' }}
                        </span>
                        <span class="flex items-center gap-1.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-neutral-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd"/><path d="M2 13.692V16a2 2 0 002 2h12a2 2 0 002-2v-2.308A24.974 24.974 0 0110 15c-2.796 0-5.487-.46-8-1.308z"/></svg>
                            {{ $remaining === 'unlimited' ? 'Unlimited postings' : $remaining . ' postings remaining this month' }}
                        </span>
                    </div>
                    {{-- Usage bar --}}
                    @if ($remaining !== 'unlimited' && $activeSub->job_postings_per_month)
                        @php $used = $activeSub->job_postings_per_month - $remaining; $pct = ($used / $activeSub->job_postings_per_month) * 100; @endphp
                        <div class="mt-4 max-w-xs">
                            <div class="flex justify-between text-xs text-neutral-500 mb-1">
                                <span>{{ $used }} used</span>
                                <span>{{ $activeSub->job_postings_per_month }} total</span>
                            </div>
                            <div class="w-full h-2 bg-neutral-100 dark:bg-neutral-800 rounded-full overflow-hidden">
                                <div class="h-full rounded-full transition-all {{ $pct > 80 ? 'bg-red-500' : ($pct > 50 ? 'bg-amber-500' : 'bg-green-500') }}" style="width: {{ min($pct, 100) }}%"></div>
                            </div>
                        </div>
                    @endif
                @else
                    <div class="flex items-center gap-3 mb-2">
                        <h3 class="text-xl font-bold text-neutral-900 dark:text-white">Free Plan</h3>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700 dark:bg-green-950/40 dark:text-green-400">Active</span>
                    </div>
                    <div class="flex items-center gap-4 text-sm text-neutral-600 dark:text-neutral-400">
                        <span class="flex items-center gap-1.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-neutral-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd"/><path d="M2 13.692V16a2 2 0 002 2h12a2 2 0 002-2v-2.308A24.974 24.974 0 0110 15c-2.796 0-5.487-.46-8-1.308z"/></svg>
                            @if ($freePlanOption)
                                {{ $freePlanOption->is_unlimited_postings ? 'Unlimited postings' : $freePlanOption->job_postings_per_month . ' postings/month' }}
                            @else
                                Limited postings
                            @endif
                        </span>
                        <span class="flex items-center gap-1.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-neutral-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/></svg>
                            Never expires
                        </span>
                    </div>
                    <p class="text-xs text-neutral-400 mt-3">Upgrade to a paid plan for priority listing and more features.</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Available Plans --}}
    <div class="mb-8">
        <h2 class="text-base font-bold text-neutral-900 dark:text-white mb-4 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#464d79]" viewBox="0 0 20 20" fill="currentColor"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/><path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"/></svg>
            Available Plans
        </h2>

        @if ($plans->isEmpty())
            <div class="bg-white dark:bg-neutral-900 rounded-xl border border-neutral-200 dark:border-neutral-800 p-8 text-center">
                <p class="text-sm text-neutral-400">No plans available for your institution type at this time.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
                @foreach ($plans as $plan)
                    <div class="bg-white dark:bg-neutral-900 rounded-2xl border border-neutral-200 dark:border-neutral-800 overflow-hidden flex flex-col shadow-sm hover:shadow-lg transition-shadow">
                        {{-- Plan header with gradient --}}
                        <div class="p-5 text-white relative overflow-hidden" style="background: linear-gradient(135deg, #464d79 0%, #48B098 100%);">
                            <div class="absolute -top-6 -right-6 w-20 h-20 bg-white/10 rounded-full"></div>
                            <div class="absolute bottom-0 left-0 w-12 h-12 bg-white/5 rounded-full translate-y-1/2 -translate-x-1/4"></div>
                            <h3 class="text-lg font-bold relative">{{ $plan->name }}</h3>
                        </div>

                        {{-- Features --}}
                        @if (is_array($plan->description) && count($plan->description) > 0)
                        <div class="p-5 border-b border-neutral-100 dark:border-neutral-800">
                            <ul class="space-y-2">
                                @foreach ($plan->description as $feature)
                                    <li class="flex items-start gap-2 text-sm text-neutral-600 dark:text-neutral-400">
                                        <svg class="w-4 h-4 text-teal-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        {{ $feature }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        {{-- Plan options --}}
                        <div class="p-5 flex-1 flex flex-col justify-end space-y-3">
                            @foreach ($plan->options as $opt)
                                <div class="flex items-center justify-between gap-3 p-3 rounded-xl border border-neutral-100 dark:border-neutral-800 bg-neutral-50/50 dark:bg-neutral-800/50">
                                    <div>
                                        <div class="flex items-baseline gap-1">
                                            @if ($opt->price <= 0)
                                                <span class="text-lg font-bold text-green-600">Free</span>
                                            @else
                                                <span class="text-lg font-bold text-neutral-900 dark:text-white">₹{{ number_format($opt->price, 0) }}</span>
                                                <span class="text-xs text-neutral-400">/{{ $opt->duration_type->label() }}</span>
                                            @endif
                                        </div>
                                        <p class="text-xs text-neutral-500 mt-0.5">
                                            {{ $opt->is_unlimited_postings ? 'Unlimited postings' : $opt->job_postings_per_month . ' postings/month' }}
                                        </p>
                                    </div>
                                    @if ($activeSub && $activeSub->recruiter_subscription_plan_option_id === $opt->id)
                                        <span class="h-8 px-3 inline-flex items-center rounded-lg text-xs font-semibold bg-green-100 text-green-700 dark:bg-green-950/40 dark:text-green-400">Current</span>
                                    @else
                                        <a href="{{ route('recruiter.checkout.show', $opt) }}" class="h-8 px-4 inline-flex items-center rounded-lg text-xs font-semibold text-white hover:opacity-90 transition-opacity shadow-sm" style="background: linear-gradient(90deg, #464d79 0%, #48B098 100%);">
                                            {{ $opt->price <= 0 ? 'Activate' : 'Subscribe' }}
                                        </a>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Billing History --}}
    <div class="bg-white dark:bg-neutral-900 rounded-xl border border-neutral-200 dark:border-neutral-800 overflow-hidden">
        <div class="px-5 py-4 border-b border-neutral-100 dark:border-neutral-800">
            <h2 class="text-sm font-semibold text-neutral-900 dark:text-white flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-[#464d79]" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/></svg>
                Subscription History
            </h2>
        </div>

        @if ($subscriptions->isEmpty())
            <div class="p-8 text-center">
                <p class="text-sm text-neutral-400">No subscription history yet.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-neutral-100 dark:border-neutral-800 text-left">
                            <th class="px-5 py-3 font-medium text-neutral-500 dark:text-neutral-400 text-xs uppercase tracking-wide">Plan</th>
                            <th class="px-5 py-3 font-medium text-neutral-500 dark:text-neutral-400 text-xs uppercase tracking-wide">Postings</th>
                            <th class="px-5 py-3 font-medium text-neutral-500 dark:text-neutral-400 text-xs uppercase tracking-wide">Status</th>
                            <th class="px-5 py-3 font-medium text-neutral-500 dark:text-neutral-400 text-xs uppercase tracking-wide">Started</th>
                            <th class="px-5 py-3 font-medium text-neutral-500 dark:text-neutral-400 text-xs uppercase tracking-wide">Expires</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-100 dark:divide-neutral-800">
                        @foreach ($subscriptions as $sub)
                            <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-800/50">
                                <td class="px-5 py-3">
                                    <span class="font-medium text-neutral-900 dark:text-white">{{ $sub->plan_name }}</span>
                                </td>
                                <td class="px-5 py-3 text-neutral-600 dark:text-neutral-400">
                                    {{ $sub->is_unlimited_postings ? 'Unlimited' : ($sub->job_postings_per_month . '/mo') }}
                                </td>
                                <td class="px-5 py-3">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $sub->status->getBadgeClasses() }}">{{ $sub->status->label() }}</span>
                                </td>
                                <td class="px-5 py-3 text-neutral-500">{{ $sub->starts_at?->format('M j, Y') ?? '-' }}</td>
                                <td class="px-5 py-3 text-neutral-500">{{ $sub->expires_at?->format('M j, Y') ?? 'Lifetime' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
