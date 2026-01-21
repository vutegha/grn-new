<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $permission = null, $guard = null): Response
    {
        // Vérifier si l'utilisateur est authentifié
        if (!auth()->guard($guard)->check()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Non authentifié'], 401);
            }
            return redirect()->route('login');
        }

        $user = auth()->guard($guard)->user();

        // Si aucune permission spécifiée, continuer
        if (!$permission) {
            return $next($request);
        }

        // Vérifier si l'utilisateur a la permission
        if (!$user->can($permission)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Vous n\'avez pas les permissions nécessaires pour accéder à cette ressource.'
                ], 403);
            }
            
            abort(403, 'Vous n\'avez pas les permissions nécessaires pour effectuer cette action.');
        }

        return $next($request);
    }
}
