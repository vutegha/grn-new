<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RateLimitJobApplications
{
    /**
     * Limiter les candidatures par IP
     * 
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Seulement pour les soumissions POST
        if ($request->isMethod('POST')) {
            $clientIp = $request->ip();
            $cacheKey = 'job_app_rate_limit_' . md5($clientIp);
            
            // Vérifier le nombre de tentatives
            $attempts = cache()->get($cacheKey, 0);
            
            if ($attempts >= 5) { // Max 5 tentatives par heure
                \Log::warning('Rate limit dépassé pour candidature', [
                    'ip' => $clientIp,
                    'attempts' => $attempts,
                    'user_agent' => $request->userAgent()
                ]);
                
                return back()->with('error', 'Trop de tentatives de candidature. Veuillez patienter une heure avant de réessayer.');
            }
            
            // Incrémenter le compteur
            cache()->put($cacheKey, $attempts + 1, now()->addHour());
        }
        
        return $next($request);
    }
}
