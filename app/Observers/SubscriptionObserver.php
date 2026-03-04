<?php

namespace App\Observers;

use App\Models\Subscription;
use App\Services\CacheService;

class SubscriptionObserver
{
    public function updated(Subscription $subscription): void
    {
        CacheService::clearSubscription($subscription->empresa_id);
        CacheService::clearDashboardAdmin();
    }

    public function created(Subscription $subscription): void
    {
        CacheService::clearSubscription($subscription->empresa_id);
        CacheService::clearDashboardAdmin();
    }

    public function deleted(Subscription $subscription): void
    {
        CacheService::clearSubscription($subscription->empresa_id);
        CacheService::clearDashboardAdmin();
    }
}
