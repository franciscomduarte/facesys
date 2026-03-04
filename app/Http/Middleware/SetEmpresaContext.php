<?php

namespace App\Http\Middleware;

use App\Services\CacheService;
use App\Services\EmpresaContextService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SetEmpresaContext
{
    public function __construct(
        private EmpresaContextService $empresaContext,
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user && !$user->isSuperAdmin()) {
            if (!$user->empresa_id) {
                Auth::logout();
                return redirect()->route('login')
                    ->with('error', 'Sua conta nao esta vinculada a nenhuma empresa.');
            }

            $empresa = CacheService::empresa($user->empresa_id);

            if (!$empresa || !$empresa->isAtiva()) {
                Auth::logout();
                return redirect()->route('login')
                    ->with('error', 'A empresa vinculada a sua conta esta inativa ou suspensa.');
            }

            $this->empresaContext->setEmpresaId($user->empresa_id);
        }

        return $next($request);
    }
}
