<div>
    <h1 class="text-2xl font-bold text-neutral-900 dark:text-white mb-6">Recruiter Subscriptions</h1>

    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
        @foreach($planStats as $plan)
        <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-4">
            <p class="text-sm font-semibold text-neutral-900 dark:text-white">{{ $plan->name }}</p>
            <p class="text-xs text-neutral-500">{{ $plan->recruiter_type->label() }}</p>
            <p class="text-2xl font-bold text-neutral-900 dark:text-white mt-2">{{ $plan->active_count }}</p>
        </div>
        @endforeach
    </div>

    <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-neutral-50 dark:bg-neutral-900 text-left">
                <tr>
                    <th class="px-4 py-3 font-medium text-neutral-500">Recruiter</th>
                    <th class="px-4 py-3 font-medium text-neutral-500">Plan</th>
                    <th class="px-4 py-3 font-medium text-neutral-500">Type</th>
                    <th class="px-4 py-3 font-medium text-neutral-500">Postings/mo</th>
                    <th class="px-4 py-3 font-medium text-neutral-500">Expires</th>
                    <th class="px-4 py-3 font-medium text-neutral-500">Admin?</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-100 dark:divide-neutral-700">
                @forelse($subscriptions as $sub)
                <tr>
                    <td class="px-4 py-3 font-medium">{{ $sub->recruiter?->name ?? '-' }}</td>
                    <td class="px-4 py-3">{{ $sub->plan_name }}</td>
                    <td class="px-4 py-3 text-xs">{{ $sub->recruiter_type->label() }}</td>
                    <td class="px-4 py-3">{{ $sub->is_unlimited_postings ? 'Unlimited' : $sub->job_postings_per_month }}</td>
                    <td class="px-4 py-3 text-neutral-500">{{ $sub->expires_at?->format('M j, Y') ?? 'Never' }}</td>
                    <td class="px-4 py-3">@if($sub->assigned_by_admin)<span class="text-xs bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full">Admin</span>@endif</td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-4 py-8 text-center text-neutral-500">No active subscriptions.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $subscriptions->links() }}</div>
</div>
