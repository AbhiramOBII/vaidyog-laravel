<?php

namespace App\Http\Controllers\Webhook;

use App\Enums\PaymentStatusEnum;
use App\Models\Payment;
use App\Models\SubscriptionPlanOption;
use App\Models\User;
use App\Services\Payment\RazorpayService;
use App\Services\Subscription\SubscriptionService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class RazorpayWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $signature = $request->header('X-Razorpay-Signature', '');

        $razorpayService = app(RazorpayService::class);

        if (!$razorpayService->verifyWebhookSignature($payload, $signature)) {
            return response()->json(['error' => 'Invalid signature'], 401);
        }

        $data = json_decode($payload, true);
        $event = $data['event'] ?? null;

        match ($event) {
            'payment.captured' => $this->handlePaymentCaptured($data),
            'payment.failed' => $this->handlePaymentFailed($data),
            default => null,
        };

        return response()->json(['status' => 'ok']);
    }

    private function handlePaymentCaptured(array $data): void
    {
        $paymentEntity = $data['payload']['payment']['entity'] ?? [];
        $orderId = $paymentEntity['order_id'] ?? null;
        $razorpayPaymentId = $paymentEntity['id'] ?? null;

        if (!$orderId) return;

        $payment = Payment::where('razorpay_order_id', $orderId)->first();
        if (!$payment) return;

        // Idempotent: skip if already completed
        if ($payment->status === PaymentStatusEnum::Completed) return;

        $payment->update([
            'status' => PaymentStatusEnum::Completed->value,
            'razorpay_payment_id' => $razorpayPaymentId,
            'paid_at' => now(),
        ]);

        // Activate subscription if not already active
        if ($payment->payable_type === 'user_subscription' && $payment->payable_id === 0) {
            $user = User::find($payment->user_id);
            $option = $this->resolveOptionFromPayment($payment);
            if ($user && $option) {
                $subscriptionService = app(SubscriptionService::class);
                $subscription = $subscriptionService->assignPlanToJobSeeker($user, $option, $payment);
                $payment->update(['payable_id' => $subscription->id]);
            }
        }
    }

    private function handlePaymentFailed(array $data): void
    {
        $paymentEntity = $data['payload']['payment']['entity'] ?? [];
        $orderId = $paymentEntity['order_id'] ?? null;

        if (!$orderId) return;

        $payment = Payment::where('razorpay_order_id', $orderId)->first();
        if (!$payment || $payment->status === PaymentStatusEnum::Completed) return;

        $payment->update(['status' => PaymentStatusEnum::Failed->value]);
    }

    private function resolveOptionFromPayment(Payment $payment): ?SubscriptionPlanOption
    {
        // The option can be inferred from amount match
        return SubscriptionPlanOption::where('price', $payment->amount)
            ->where('is_active', true)
            ->first();
    }
}
