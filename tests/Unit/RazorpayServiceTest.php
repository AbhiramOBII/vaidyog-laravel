<?php

namespace Tests\Unit;

use App\Services\Payment\RazorpayService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RazorpayServiceTest extends TestCase
{
    use RefreshDatabase;
    public function test_verify_signature_returns_true_for_valid_hmac(): void
    {
        config(['services.razorpay.key_secret' => 'test_secret_key']);

        $service = new RazorpayService();
        $orderId = 'order_123abc';
        $paymentId = 'pay_456def';
        $expected = hash_hmac('sha256', $orderId . '|' . $paymentId, 'test_secret_key');

        $this->assertTrue($service->verifySignature($orderId, $paymentId, $expected));
    }

    public function test_verify_signature_returns_false_for_tampered_signature(): void
    {
        config(['services.razorpay.key_secret' => 'test_secret_key']);

        $service = new RazorpayService();
        $this->assertFalse($service->verifySignature('order_123', 'pay_456', 'tampered_signature'));
    }

    public function test_verify_webhook_signature_returns_true_for_valid_payload(): void
    {
        config(['services.razorpay.webhook_secret' => 'webhook_secret_123']);

        $service = new RazorpayService();
        $payload = '{"event":"payment.captured"}';
        $expected = hash_hmac('sha256', $payload, 'webhook_secret_123');

        $this->assertTrue($service->verifyWebhookSignature($payload, $expected));
    }

    public function test_verify_webhook_signature_returns_false_for_invalid(): void
    {
        config(['services.razorpay.webhook_secret' => 'webhook_secret_123']);

        $service = new RazorpayService();
        $this->assertFalse($service->verifyWebhookSignature('payload', 'bad_sig'));
    }
}
