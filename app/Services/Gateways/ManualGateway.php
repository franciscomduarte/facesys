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
}
