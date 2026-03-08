<?php

namespace App\Http\Middleware;

use App\Services\SubscriptionService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPlanLimits
{
    public function __construct(
        private SubscriptionService $subscriptionService,
    ) {}

    public function handle(Request $request, Closure $next, string $recurso): Response
    {
        $user = $request->user();

        if (!$user || $user->isSuperAdmin()) {
            return $next($request);
        }

        // Apenas verifica em requisicoes de criacao
        if (!in_array($request->method(), ['POST'])) {
            return $next($request);
        }

        $empresa = $user->empresa;
        if (!$empresa) {
            abort(403, 'Conta nao vinculada a uma empresa.');
        }

        if (!$this->subscriptionService->verificarLimite($empresa, $recurso)) {
            abort(403, "Limite de {$recurso} atingido no seu plano atual. Faca upgrade para continuar.");
        }

        return $next($request);
    }
}
