<div class="max-w-lg mx-auto py-12 px-4" x-data="{ processing: false }"
    x-on:razorpay-error.window="processing = false"
    x-on:razorpay-order-created.window="
        processing = true;
        let data = $event.detail[0];
        try {
            let rzp = new Razorpay({
                key: data.key,
                amount: data.amount,
                currency: 'INR',
                name: data.name,
                description: data.description,
                order_id: data.order_id,
                prefill: { name: data.user_name, email: data.user_email, contact: data.user_phone },
                handler: function(response) {
                    $wire.verify(response.razorpay_payment_id, response.razorpay_order_id, response.razorpay_signature);
                },
                modal: { ondismiss: function() { processing = false; } }
            });
            rzp.open();
        } catch(e) { processing = false; }
    ">

    <h1 class="text-2xl font-bold text-neutral-900 dark:text-white mb-6">Checkout</h1>

    @if($errorMessage)
    <div class="mb-4 p-3 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm">{{ $errorMessage }}</div>
    @endif

    <div class="bg-white dark:bg-neutral-800 rounded-2xl border border-neutral-200 dark:border-neutral-700 p-6">
        <h2 class="text-sm font-semibold text-neutral-500 uppercase mb-4">Order Summary</h2>
        <div class="space-y-3 mb-6">
            <div class="flex justify-between">
                <span class="text-neutral-600 dark:text-neutral-400">Plan</span>
                <span class="font-medium text-neutral-900 dark:text-white">{{ $planOption->plan->name }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-neutral-600 dark:text-neutral-400">Option</span>
                <span class="font-medium text-neutral-900 dark:text-white">{{ $planOption->label }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-neutral-600 dark:text-neutral-400">Duration</span>
                <span class="font-medium text-neutral-900 dark:text-white">{{ $planOption->duration_value }} {{ $planOption->duration_type->label() }}</span>
            </div>
            <div class="flex justify-between border-t border-neutral-100 dark:border-neutral-700 pt-3">
                <span class="font-semibold text-neutral-900 dark:text-white">Total</span>
                <span class="text-xl font-bold text-neutral-900 dark:text-white">₹{{ number_format($planOption->price) }}</span>
            </div>
        </div>
        <p class="text-xs text-neutral-500 mb-4">Prices are inclusive of applicable taxes.</p>

        <button wire:click="initiate" :disabled="processing" class="w-full h-12 rounded-xl text-sm font-semibold bg-[#464d79] text-white hover:bg-[#464d79]/90 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
            <span x-show="!processing">Pay ₹{{ number_format($planOption->price) }} with Razorpay</span>
            <span x-show="processing" x-cloak>Processing...</span>
        </button>
    </div>
</div>

@push('scripts')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
@endpush
