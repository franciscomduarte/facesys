<?php

namespace App\Services\Gateways;

use App\Contracts\PaymentGatewayInterface;
use App\Models\Empresa;
use App\Models\Subscription;
use Illuminate\Support\Str;

class ManualGateway implements PaymentGatewayInterface
{
    public function createCustomer(Empresa $empresa): string
    {
        return 'manual_cust_' . Str::random(16);
    }

    public function createSubscription(Subscription $subscription): string
    {
        return 'manual_sub_' . Str::random(16);
    }

    public function cancelSubscription(string $gatewaySubscriptionId): bool
    {
        return true;
    }

    public function getSubscriptionStatus(string $gatewaySubscriptionId): string
    {
        return 'active';
    }

    public function createPixPayment(Subscription $subscription, float $amount): array
    {
        return [
            'gateway_payment_id' => 'manual_pay_' . Str::random(16),
            'pix_qr_code' => null,
            'pix_copy_paste' => null,
            'expiration_date' => now()->addDays(3)->toIso8601String(),
            'invoice_url' => null,
        ];
    }

    public function createCardPayment(Subscription $subscription, float $amount, array $cardData): array
    {
        return [
            'gateway_payment_id' => 'manual_pay_' . Str::random(16),
            'status' => 'paid',
            'invoice_url' => null,
        ];
    }

    public function verifyWebhookSignature(string $payload, string $signature): bool
    {
        return true;
    }
}
