<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CanModerate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Vérifier si l'utilisateur est authentifié et a le rôle approprié
        if (!auth()->check()) {
            abort(401, 'Non authentifié');
        }

        $user = auth()->user();
        
        // Utiliser la méthode canModerate() du modèle User
        if (!$user->canModerate()) {
            abort(403, 'Vous n\'avez pas les permissions pour modérer le contenu');
        }

        return $next($request);
    }
}
