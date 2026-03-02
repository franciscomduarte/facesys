<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckFeature
{
    public function handle(Request $request, Closure $next, string $feature): Response
    {
        $user = Auth::user();

        if (!$user || $user->isSuperAdmin()) {
            return $next($request);
        }

        $empresa = $user->empresa;
        if (!$empresa) {
            return $next($request);
        }

        $subscription = $empresa->subscription;

        if (!$subscription || !$subscription->temFuncionalidade($feature)) {
            $mensagem = 'A funcionalidade "' . ucfirst(str_replace('_', ' ', $feature)) . '" nao esta disponivel no seu plano atual.';

            if ($request->expectsJson()) {
                return response()->json(['error' => $mensagem], 403);
            }

            return redirect()->route('dashboard')->with('error', $mensagem);
        }

        return $next($request);
    }
}
