<div class="max-w-lg mx-auto py-10" x-data="{ processing: false }"
    x-on:razorpay-order-created.window="
        processing = true;
        let data = $event.detail[0];
        let rzp = new Razorpay({
            key: data.key, amount: data.amount, currency: 'INR', name: data.name, description: data.description, order_id: data.order_id,
            prefill: { name: data.user_name, email: data.user_email, contact: data.user_phone },
            handler: function(r) { $wire.verify(r.razorpay_payment_id, r.razorpay_order_id, r.razorpay_signature); },
            modal: { ondismiss: function() { processing = false; } }
        });
        rzp.open();
    ">
    <h1 class="text-2xl font-bold text-neutral-900 dark:text-white mb-6">Feature a Job</h1>
    @if($errorMessage)<div class="mb-4 p-3 rounded-lg bg-red-50 text-red-700 text-sm">{{ $errorMessage }}</div>@endif

    <div class="bg-white dark:bg-neutral-800 rounded-2xl border border-neutral-200 dark:border-neutral-700 p-6 space-y-4">
        <div><span class="text-neutral-500 text-sm">Job</span><p class="font-medium text-neutral-900 dark:text-white">{{ $job->job_title }}</p></div>

        @if($featuredPlan)
        <div class="border-t border-neutral-100 pt-4 space-y-2">
            <div class="flex justify-between text-sm"><span class="text-neutral-600">Plan</span><span class="font-medium">{{ $featuredPlan->name }}</span></div>
            <div class="flex justify-between text-sm"><span class="text-neutral-600">Featured duration</span><span class="font-medium">{{ $featuredPlan->featured_duration_days }} days</span></div>
            <div class="flex justify-between text-sm"><span class="text-neutral-600">Featured until</span><span class="font-medium">{{ now()->addDays($featuredPlan->featured_duration_days)->format('M j, Y') }}</span></div>
            <div class="flex justify-between border-t pt-3"><span class="font-semibold">Price</span><span class="text-xl font-bold">₹{{ number_format($featuredPlan->price_per_post) }}</span></div>
        </div>
        <button wire:click="initiate" :disabled="processing" class="w-full h-12 rounded-xl text-sm font-semibold bg-[#4ab098] text-white hover:bg-[#4ab098]/90 disabled:opacity-50">
            <span x-show="!processing">Feature this job for ₹{{ number_format($featuredPlan->price_per_post) }}</span><span x-show="processing" x-cloak>Processing...</span>
        </button>
        @else
        <p class="text-neutral-500">No featured job plan available for your institution type.</p>
        @endif
    </div>
</div>
@push('scripts')<script src="https://checkout.razorpay.com/v1/checkout.js"></script>@endpush
