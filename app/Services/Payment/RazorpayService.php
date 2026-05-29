<?php

namespace App\Services\Payment;

class RazorpayService
{
    private $api = null;

    private function getApi()
    {
        if ($this->api === null) {
            $apiClass = '\Razorpay\Api\Api';
            $this->api = new $apiClass(
                config('services.razorpay.key_id'),
                config('services.razorpay.key_secret')
            );
        }
        return $this->api;
    }

    public function createOrder(float $amount, string $currency, string $receipt, array $notes = []): array
    {
        $order = $this->getApi()->order->create([
            'amount' => (int) ($amount * 100), // Razorpay expects paise
            'currency' => $currency,
            'receipt' => $receipt,
            'notes' => $notes,
        ]);

        return [
            'id' => $order->id,
            'amount' => $order->amount,
            'currency' => $order->currency,
        ];
    }

    public function verifySignature(string $orderId, string $paymentId, string $signature): bool
    {
        $expected = hash_hmac('sha256', $orderId . '|' . $paymentId, config('services.razorpay.key_secret'));
        return hash_equals($expected, $signature);
    }

    public function verifyWebhookSignature(string $payload, string $signature): bool
    {
        $expected = hash_hmac('sha256', $payload, config('services.razorpay.webhook_secret'));
        return hash_equals($expected, $signature);
    }
}
