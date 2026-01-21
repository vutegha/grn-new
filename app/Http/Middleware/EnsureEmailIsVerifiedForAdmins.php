<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmailIsVerifiedForAdmins
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Si l'utilisateur n'est pas connecté, passer au middleware suivant
        if (!$user) {
            return $next($request);
        }

        // Vérifier si l'utilisateur a des rôles administratifs
        $adminRoles = ['admin', 'super-admin', 'moderator'];
        $hasAdminRole = $user->hasAnyRole($adminRoles);

        // Si l'utilisateur a des rôles administratifs mais n'a pas vérifié son email
        if ($hasAdminRole && !$user->hasVerifiedEmail()) {
            // Log this security event
            \Log::warning('Unverified admin attempted to access admin area', [
                'user_id' => $user->id,
                'email' => $user->email,
                'roles' => $user->roles->pluck('name')->toArray(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url' => $request->fullUrl()
            ]);

            // Rediriger vers la page de vérification d'email
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Votre adresse email doit être vérifiée pour accéder aux fonctionnalités administratives.',
                    'redirect' => route('verification.notice')
                ], 403);
            }

            return redirect()->route('verification.notice')
                ->with('warning', 'Votre adresse email doit être vérifiée pour accéder aux fonctionnalités administratives.');
        }

        return $next($request);
    }
}
