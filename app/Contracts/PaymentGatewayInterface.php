<?php

namespace App\Contracts;

use App\Models\Empresa;
use App\Models\Subscription;

interface PaymentGatewayInterface
{
    public function createCustomer(Empresa $empresa): string;

    public function createSubscription(Subscription $subscription): string;

    public function cancelSubscription(string $gatewaySubscriptionId): bool;

    public function getSubscriptionStatus(string $gatewaySubscriptionId): string;

    public function createPixPayment(Subscription $subscription, float $amount): array;

    public function createCardPayment(Subscription $subscription, float $amount, array $cardData): array;

    public function verifyWebhookSignature(string $payload, string $signature): bool;
}
