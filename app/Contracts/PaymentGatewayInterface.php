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
}
