<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->appendToGroup('web', \App\Http\Middleware\SetEmpresaContext::class);
        $middleware->appendToGroup('web', \App\Http\Middleware\CheckSubscription::class);
        $middleware->appendToGroup('web', \App\Http\Middleware\ForcePasswordChange::class);
        $middleware->alias([
            'check.feature' => \App\Http\Middleware\CheckFeature::class,
            'check.module' => \App\Http\Middleware\CheckPlanModule::class,
            'check.limits' => \App\Http\Middleware\CheckPlanLimits::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
