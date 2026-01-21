<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SuperAdminAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Si l'utilisateur est un super admin, il a accès à tout
        if (auth()->check() && auth()->user()->isSuperAdmin()) {
            return $next($request);
        }

        // Sinon, continuer avec les vérifications normales
        return $next($request);
    }
}
