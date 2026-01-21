<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Protection XSS
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        
        // Protection contre le sniffing de type MIME
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        
        // Protection contre le clickjacking
        $response->headers->set('X-Frame-Options', 'DENY');
        
        // Politique de sécurité du contenu (CSP)
        $csp = "default-src 'self'; " .
               "script-src 'self' 'unsafe-inline' 'unsafe-eval' cdnjs.cloudflare.com cdn.jsdelivr.net; " .
               "style-src 'self' 'unsafe-inline' fonts.googleapis.com cdnjs.cloudflare.com; " .
               "font-src 'self' fonts.gstatic.com; " .
               "img-src 'self' data: blob: *; " .
               "connect-src 'self'; " .
               "frame-ancestors 'none';";
        
        $response->headers->set('Content-Security-Policy', $csp);
        
        // Protection HTTPS (si en production)
        if (config('app.env') === 'production') {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        }
        
        // Politique de référent
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        
        // Protection contre les fuites DNS
        $response->headers->set('X-DNS-Prefetch-Control', 'off');

        return $response;
    }
}
