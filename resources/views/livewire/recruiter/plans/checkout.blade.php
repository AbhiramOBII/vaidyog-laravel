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
    <h1 class="text-2xl font-bold text-neutral-900 dark:text-white mb-6">Checkout</h1>
    @if($errorMessage)<div class="mb-4 p-3 rounded-lg bg-red-50 text-red-700 text-sm">{{ $errorMessage }}</div>@endif
    <div class="bg-white dark:bg-neutral-800 rounded-2xl border border-neutral-200 dark:border-neutral-700 p-6 space-y-4">
        <div class="flex justify-between"><span class="text-neutral-600">Plan</span><span class="font-medium">{{ $planOption->plan->name }}</span></div>
        <div class="flex justify-between"><span class="text-neutral-600">Option</span><span class="font-medium">{{ $planOption->label }}</span></div>
        <div class="flex justify-between"><span class="text-neutral-600">Postings/month</span><span class="font-medium">{{ $planOption->is_unlimited_postings ? 'Unlimited' : $planOption->job_postings_per_month }}</span></div>
        <div class="flex justify-between border-t pt-3"><span class="font-semibold">Total</span><span class="text-xl font-bold">₹{{ number_format($planOption->price) }}</span></div>
        <button wire:click="initiate" :disabled="processing" class="w-full h-12 rounded-xl text-sm font-semibold bg-[#464d79] text-white hover:bg-[#464d79]/90 disabled:opacity-50">
            <span x-show="!processing">Pay ₹{{ number_format($planOption->price) }}</span><span x-show="processing" x-cloak>Processing...</span>
        </button>
    </div>
</div>
@push('scripts')<script src="https://checkout.razorpay.com/v1/checkout.js"></script>@endpush
