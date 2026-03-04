<?php

namespace App\Providers;

use App\Contracts\PaymentGatewayInterface;
use App\Models\Empresa;
use App\Models\Subscription;
use App\Observers\EmpresaObserver;
use App\Observers\SubscriptionObserver;
use App\Services\EmpresaContextService;
use App\Services\Gateways\ManualGateway;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(EmpresaContextService::class);

        $this->app->bind(PaymentGatewayInterface::class, function () {
            return match (config('billing.gateway')) {
                default => new ManualGateway(),
            };
        });
    }

    public function boot(): void
    {
        Empresa::observe(EmpresaObserver::class);
        Subscription::observe(SubscriptionObserver::class);
    }
}
