<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ForcePasswordChange
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user && $user->force_password_change) {
            $allowedRoutes = ['password.force-change', 'password.force-update', 'logout'];

            if (!in_array($request->route()?->getName(), $allowedRoutes)) {
                return redirect()->route('password.force-change');
            }
        }

        return $next($request);
    }
}
