<?php

namespace App\Services\Payment;

use App\Models\SiteSetting;

class RazorpayService
{
    private $api = null;

    private function getKeyId(): string
    {
        return (string) (SiteSetting::get('razorpay_key_id') ?? config('services.razorpay.key_id') ?? '');
    }

    private function getKeySecret(): string
    {
        return (string) (SiteSetting::get('razorpay_key_secret') ?? config('services.razorpay.key_secret') ?? '');
    }

    private function getWebhookSecret(): string
    {
        return (string) (SiteSetting::get('razorpay_webhook_secret') ?? config('services.razorpay.webhook_secret') ?? '');
    }

    private function getApi()
    {
        if ($this->api === null) {
            $apiClass = '\Razorpay\Api\Api';
            $this->api = new $apiClass(
                $this->getKeyId(),
                $this->getKeySecret()
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
        $expected = hash_hmac('sha256', $orderId . '|' . $paymentId, $this->getKeySecret());
        return hash_equals($expected, $signature);
    }

    public function verifyWebhookSignature(string $payload, string $signature): bool
    {
        $expected = hash_hmac('sha256', $payload, $this->getWebhookSecret());
        return hash_equals($expected, $signature);
    }
}
