<?php

namespace App\Livewire\Recruiter\Jobs;

use App\Enums\PaymentStatusEnum;
use App\Models\FeaturedJobPlan;
use App\Models\JobPosting;
use App\Models\Payment;
use App\Services\Payment\RazorpayService;
use App\Services\Subscription\SubscriptionService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.recruiter', ['pageTitle' => 'Feature a Job'])]
class FeatureJob extends Component
{
    public JobPosting $job;
    public ?FeaturedJobPlan $featuredPlan = null;
    public ?int $paymentId = null;
    public string $errorMessage = '';

    public function mount(JobPosting $job): void
    {
        $this->job = $job;

        if ($job->recruiter_id !== auth()->id()) {
            abort(403);
        }

        $medType = auth()->user()->profile?->med_type?->value;
        $this->featuredPlan = FeaturedJobPlan::where('is_active', true)
            ->where(fn($q) => $q->where('recruiter_type', $medType)->orWhereNull('recruiter_type'))
            ->first();
    }

    public function initiate(): void
    {
        if (!$this->featuredPlan) {
            $this->errorMessage = 'No featured plan available.';
            return;
        }

        $user = auth()->user();

        $payment = Payment::create([
            'user_id' => $user->id,
            'user_type' => 'recruiter',
            'payable_type' => 'featured_job_purchase',
            'payable_id' => 0,
            'amount' => $this->featuredPlan->price_per_post,
            'currency' => 'INR',
            'status' => PaymentStatusEnum::Pending->value,
            'payment_method' => 'razorpay',
        ]);

        $this->paymentId = $payment->id;

        try {
            $razorpayService = app(RazorpayService::class);
            $order = $razorpayService->createOrder(
                (float) $this->featuredPlan->price_per_post,
                'INR',
                'feat_' . $payment->id,
                ['job_id' => $this->job->id, 'recruiter_id' => $user->id]
            );

            $payment->update(['razorpay_order_id' => $order['id']]);

            $this->dispatch('razorpay-order-created', [
                'order_id' => $order['id'],
                'amount' => $order['amount'],
                'key' => \App\Models\SiteSetting::get('razorpay_key_id', config('services.razorpay.key_id', '')),
                'name' => 'Vaidyog',
                'description' => 'Feature: ' . $this->job->job_title,
                'user_name' => $user->name,
                'user_email' => $user->email,
                'user_phone' => $user->phone ?? '',
            ]);
        } catch (\Exception $e) {
            $payment->update(['status' => PaymentStatusEnum::Failed->value]);
            $this->errorMessage = 'Failed to create payment: ' . $e->getMessage();
            $this->dispatch('razorpay-error');
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
            $purchase = $subscriptionService->featureJob(
                auth()->user(),
                $this->job,
                $this->featuredPlan,
                $payment
            );

            $payment->update(['payable_id' => $purchase->id]);

            session()->flash('success', 'Your job is now featured!');
            $this->redirect(route('recruiter.jobs.show', $this->job), navigate: true);
        } else {
            $payment->update(['status' => PaymentStatusEnum::Failed->value]);
            $this->errorMessage = 'Payment verification failed.';
        }
    }

    public function render()
    {
        return view('livewire.recruiter.jobs.feature-job');
    }
}
