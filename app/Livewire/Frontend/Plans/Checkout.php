<?php

namespace App\Livewire\Frontend\Plans;

use App\Enums\PaymentStatusEnum;
use App\Models\Payment;
use App\Models\SubscriptionPlanOption;
use App\Services\Payment\RazorpayService;
use App\Services\Subscription\SubscriptionService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app', ['pageTitle' => 'Checkout'])]
class Checkout extends Component
{
    public SubscriptionPlanOption $planOption;
    public ?string $razorpayOrderId = null;
    public ?int $paymentId = null;
    public string $errorMessage = '';
    public bool $success = false;

    public function mount(SubscriptionPlanOption $planOption): void
    {
        $this->planOption = $planOption->load('plan');

        if (!$planOption->is_active || !$planOption->plan->is_active) {
            abort(404);
        }
    }

    public function initiate(): void
    {
        $user = auth()->user();

        // Create pending payment
        $payment = Payment::create([
            'user_id' => $user->id,
            'user_type' => 'job_seeker',
            'payable_type' => 'user_subscription',
            'payable_id' => 0, // Will be updated after subscription is created
            'amount' => $this->planOption->price,
            'currency' => 'INR',
            'status' => PaymentStatusEnum::Pending->value,
            'payment_method' => 'razorpay',
        ]);

        $this->paymentId = $payment->id;

        try {
            $razorpayService = app(RazorpayService::class);
            $order = $razorpayService->createOrder(
                (float) $this->planOption->price,
                'INR',
                'pay_' . $payment->id,
                ['plan' => $this->planOption->plan->name, 'user_id' => $user->id]
            );

            $payment->update(['razorpay_order_id' => $order['id']]);
            $this->razorpayOrderId = $order['id'];

            $this->dispatch('razorpay-order-created', [
                'order_id' => $order['id'],
                'amount' => $order['amount'],
                'key' => config('services.razorpay.key_id'),
                'name' => 'Vaidyog',
                'description' => $this->planOption->plan->name . ' - ' . $this->planOption->label,
                'user_name' => $user->name,
                'user_email' => $user->email,
                'user_phone' => $user->phone ?? '',
            ]);
        } catch (\Exception $e) {
            $payment->update(['status' => PaymentStatusEnum::Failed->value]);
            $this->errorMessage = 'Failed to create payment order. Please try again.';
        }
    }

    public function verify(string $razorpayPaymentId, string $razorpayOrderId, string $razorpaySignature): void
    {
        $payment = Payment::find($this->paymentId);
        if (!$payment) {
            $this->errorMessage = 'Payment not found.';
            return;
        }

        $razorpayService = app(RazorpayService::class);
        $isValid = $razorpayService->verifySignature($razorpayOrderId, $razorpayPaymentId, $razorpaySignature);

        if ($isValid) {
            $payment->update([
                'status' => PaymentStatusEnum::Completed->value,
                'razorpay_payment_id' => $razorpayPaymentId,
                'razorpay_signature' => $razorpaySignature,
                'paid_at' => now(),
            ]);

            // Activate subscription
            $subscriptionService = app(SubscriptionService::class);
            $subscription = $subscriptionService->assignPlanToJobSeeker(
                auth()->user(),
                $this->planOption,
                $payment
            );

            // Update payment with actual payable_id
            $payment->update(['payable_id' => $subscription->id]);

            $this->success = true;
            session()->flash('success', 'Plan activated successfully!');
            $this->redirect(route('jobseeker.plan'), navigate: true);
        } else {
            $payment->update(['status' => PaymentStatusEnum::Failed->value]);
            $this->errorMessage = 'Payment verification failed. If you were charged, please contact support.';
        }
    }

    public function render()
    {
        return view('livewire.frontend.plans.checkout');
    }
}
