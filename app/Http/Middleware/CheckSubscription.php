<?php

namespace App\Http\Middleware;

use App\Services\CacheService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscription
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user || $user->isSuperAdmin()) {
            return $next($request);
        }

        if (!$user->empresa_id) {
            return $next($request);
        }

        $subscription = CacheService::subscription($user->empresa_id);

        // Sem subscription — bloquear
        if (!$subscription) {
            return $this->bloquear($request, 'Sua empresa nao possui um plano ativo. Entre em contato com o suporte.');
        }

        // Cancelada ou expirada
        if ($subscription->isCancelada() || $subscription->isExpirada()) {
            return $this->bloquear($request, 'O plano da sua empresa esta cancelado ou expirado. Entre em contato com o suporte para reativar.');
        }

        // Trial expirado
        if ($subscription->trialExpirado()) {
            return $this->bloquear($request, 'O periodo de teste da sua empresa expirou. Entre em contato com o suporte para ativar um plano.');
        }

        // Inadimplente — apenas leitura
        if ($subscription->isInadimplente() && !$request->isMethod('GET')) {
            return redirect()->back()->with('error', 'Sua empresa esta inadimplente. Apenas visualizacao esta disponivel ate a regularizacao do pagamento.');
        }

        return $next($request);
    }

    private function bloquear(Request $request, string $mensagem): Response
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => $mensagem], 403);
        }

        return redirect()->route('dashboard')->with('error', $mensagem);
    }
}
