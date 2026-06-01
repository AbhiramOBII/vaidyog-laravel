<?php

namespace App\Livewire\Recruiter\Plans;

use App\Enums\PaymentStatusEnum;
use App\Models\Payment;
use App\Models\RecruiterSubscriptionPlanOption;
use App\Services\Payment\RazorpayService;
use App\Services\Subscription\SubscriptionService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.recruiter', ['pageTitle' => 'Checkout'])]
class RecruiterCheckout extends Component
{
    public RecruiterSubscriptionPlanOption $planOption;
    public ?string $razorpayOrderId = null;
    public ?int $paymentId = null;
    public string $errorMessage = '';

    public function mount(RecruiterSubscriptionPlanOption $planOption): void
    {
        $this->planOption = $planOption->load('plan');
    }

    public function initiate(): void
    {
        $user = auth()->user();

        $payment = Payment::create([
            'user_id' => $user->id,
            'user_type' => 'recruiter',
            'payable_type' => 'recruiter_subscription',
            'payable_id' => 0,
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
                'rpay_' . $payment->id,
                ['plan' => $this->planOption->plan->name, 'recruiter_id' => $user->id]
            );

            $payment->update(['razorpay_order_id' => $order['id']]);
            $this->razorpayOrderId = $order['id'];

            $this->dispatch('razorpay-order-created', [
                'order_id' => $order['id'],
                'amount' => $order['amount'],
                'key' => \App\Models\SiteSetting::get('razorpay_key_id', config('services.razorpay.key_id', '')),
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

            $subscriptionService = app(SubscriptionService::class);
            $subscription = $subscriptionService->assignPlanToRecruiter(
                auth()->user(),
                $this->planOption,
                $payment
            );

            $payment->update(['payable_id' => $subscription->id]);

            session()->flash('success', 'Plan activated successfully!');
            $this->redirect(route('recruiter.plan'), navigate: true);
        } else {
            $payment->update(['status' => PaymentStatusEnum::Failed->value]);
            $this->errorMessage = 'Payment verification failed.';
        }
    }

    public function render()
    {
        return view('livewire.recruiter.plans.checkout');
    }
}
