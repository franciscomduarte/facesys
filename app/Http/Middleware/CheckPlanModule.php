<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPlanModule
{
    public function handle(Request $request, Closure $next, string $moduleSlug): Response
    {
        $user = $request->user();

        if (!$user || $user->isSuperAdmin()) {
            return $next($request);
        }

        $empresa = $user->empresa;
        if (!$empresa) {
            abort(403, 'Conta nao vinculada a uma empresa.');
        }

        $subscription = $empresa->subscription;
        if (!$subscription || !$subscription->plano) {
            abort(403, 'Nenhuma assinatura ativa.');
        }

        $hasModule = $subscription->plano->modules()
            ->where('slug', $moduleSlug)
            ->exists();

        if (!$hasModule) {
            abort(403, 'Seu plano nao inclui acesso a este modulo. Faca upgrade para desbloquear.');
        }

        return $next($request);
    }
}
