<?php

namespace App\Livewire\Admin\Payments;

use App\Models\Payment;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.admin', ['pageTitle' => 'Payments'])]
class PaymentIndex extends Component
{
    use WithPagination;

    public string $search = '';
    public string $userType = '';
    public string $paymentType = '';
    public string $status = '';

    public function updatedSearch(): void { $this->resetPage(); }
    public function updatedUserType(): void { $this->resetPage(); }
    public function updatedPaymentType(): void { $this->resetPage(); }
    public function updatedStatus(): void { $this->resetPage(); }

    public function render()
    {
        $query = Payment::with('user')->latest();

        if ($this->search) {
            $query->where(function ($q) {
                $q->whereHas('user', fn($u) => $u->where('name', 'like', "%{$this->search}%"))
                  ->orWhere('razorpay_payment_id', 'like', "%{$this->search}%")
                  ->orWhere('razorpay_order_id', 'like', "%{$this->search}%");
            });
        }

        if ($this->userType) {
            $query->where('user_type', $this->userType);
        }

        if ($this->paymentType) {
            $query->where('payable_type', $this->paymentType);
        }

        if ($this->status) {
            $query->where('status', $this->status);
        }

        return view('livewire.admin.payments.payment-index', [
            'payments' => $query->paginate(20),
            'totalRevenue' => Payment::completed()->sum('amount'),
            'monthRevenue' => Payment::completed()->whereMonth('paid_at', now()->month)->whereYear('paid_at', now()->year)->sum('amount'),
            'failedCount' => Payment::failed()->whereMonth('created_at', now()->month)->count(),
            'pendingCount' => Payment::pending()->count(),
        ]);
    }
}
