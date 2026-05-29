<div>
    <h1 class="text-2xl font-bold text-neutral-900 dark:text-white mb-6">Payments</h1>

    {{-- Stats --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-4">
            <p class="text-xs text-neutral-500 uppercase font-medium">Total Revenue</p>
            <p class="text-2xl font-bold text-neutral-900 dark:text-white mt-1">₹{{ number_format($totalRevenue) }}</p>
        </div>
        <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-4">
            <p class="text-xs text-neutral-500 uppercase font-medium">This Month</p>
            <p class="text-2xl font-bold text-neutral-900 dark:text-white mt-1">₹{{ number_format($monthRevenue) }}</p>
        </div>
        <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-4">
            <p class="text-xs text-neutral-500 uppercase font-medium">Failed (this month)</p>
            <p class="text-2xl font-bold text-red-600 mt-1">{{ $failedCount }}</p>
        </div>
        <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 p-4">
            <p class="text-xs text-neutral-500 uppercase font-medium">Pending</p>
            <p class="text-2xl font-bold text-amber-600 mt-1">{{ $pendingCount }}</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="flex flex-wrap gap-3 mb-4">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search name, payment ID..." class="h-9 px-3 border rounded-lg text-sm w-64" />
        <select wire:model.live="userType" class="h-9 px-3 border rounded-lg text-sm">
            <option value="">All Users</option>
            <option value="job_seeker">Job Seekers</option>
            <option value="recruiter">Recruiters</option>
        </select>
        <select wire:model.live="paymentType" class="h-9 px-3 border rounded-lg text-sm">
            <option value="">All Types</option>
            <option value="user_subscription">Subscription</option>
            <option value="recruiter_subscription">Recruiter Sub</option>
            <option value="featured_job_purchase">Featured Job</option>
        </select>
        <select wire:model.live="status" class="h-9 px-3 border rounded-lg text-sm">
            <option value="">All Status</option>
            <option value="completed">Completed</option>
            <option value="pending">Pending</option>
            <option value="failed">Failed</option>
            <option value="refunded">Refunded</option>
        </select>
    </div>

    {{-- Table --}}
    <div class="bg-white dark:bg-neutral-800 rounded-xl border border-neutral-200 dark:border-neutral-700 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-neutral-50 dark:bg-neutral-900 text-left">
                <tr>
                    <th class="px-4 py-3 font-medium text-neutral-500">User</th>
                    <th class="px-4 py-3 font-medium text-neutral-500">Type</th>
                    <th class="px-4 py-3 font-medium text-neutral-500">Amount</th>
                    <th class="px-4 py-3 font-medium text-neutral-500">Status</th>
                    <th class="px-4 py-3 font-medium text-neutral-500">Razorpay ID</th>
                    <th class="px-4 py-3 font-medium text-neutral-500">Paid At</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-neutral-100 dark:divide-neutral-700">
                @forelse($payments as $payment)
                <tr>
                    <td class="px-4 py-3">
                        <p class="font-medium text-neutral-900 dark:text-white">{{ $payment->user?->name ?? '-' }}</p>
                        <p class="text-xs text-neutral-500">{{ $payment->user_type }}</p>
                    </td>
                    <td class="px-4 py-3"><span class="text-xs px-2 py-0.5 rounded-full bg-neutral-100 text-neutral-600">{{ str_replace('_', ' ', $payment->payable_type) }}</span></td>
                    <td class="px-4 py-3 font-medium">₹{{ number_format($payment->amount) }}</td>
                    <td class="px-4 py-3"><span class="text-xs px-2 py-0.5 rounded-full {{ $payment->status->getBadgeClasses() }}">{{ $payment->status->label() }}</span></td>
                    <td class="px-4 py-3 text-xs text-neutral-500 font-mono">{{ $payment->razorpay_payment_id ? Str::limit($payment->razorpay_payment_id, 18) : '-' }}</td>
                    <td class="px-4 py-3 text-neutral-500">{{ $payment->paid_at?->format('M j, Y H:i') ?? '-' }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-4 py-8 text-center text-neutral-500">No payments found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $payments->links() }}</div>
</div>
