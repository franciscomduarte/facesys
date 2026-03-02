<?php

namespace App\Providers;

use App\Services\EmpresaContextService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(EmpresaContextService::class);
    }

    public function boot(): void
    {
        //
    }
}
