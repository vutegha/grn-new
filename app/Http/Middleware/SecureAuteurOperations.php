<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class SecureAuteurOperations
{
    /**
     * Handle an incoming request for author operations security
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Log all author-related operations for security auditing
        if ($request->route() && str_contains($request->route()->getName(), 'auteur')) {
            Log::info('Author operation attempted', [
                'user_id' => auth()->id() ?? 'anonymous',
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'route' => $request->route()->getName(),
                'method' => $request->method(),
                'parameters' => $request->route()->parameters(),
                'timestamp' => now()->toDateTimeString()
            ]);
        }

        // Check for suspicious patterns in form data
        if ($request->isMethod('POST') || $request->isMethod('PUT')) {
            $this->validateInputSecurity($request);
        }

        // Rate limiting for photo uploads
        if ($request->hasFile('photo')) {
            $this->validatePhotoSecurity($request);
        }

        return $next($request);
    }

    /**
     * Validate input security for author operations
     */
    private function validateInputSecurity(Request $request): void
    {
        $suspiciousPatterns = [
            '/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/mi',
            '/javascript:/i',
            '/on\w+\s*=/i',
            '/<iframe/i',
            '/<object/i',
            '/<embed/i',
            '/data:text\/html/i',
        ];

        $inputData = $request->except(['_token', '_method', 'photo']);

        foreach ($inputData as $key => $value) {
            if (is_string($value)) {
                foreach ($suspiciousPatterns as $pattern) {
                    if (preg_match($pattern, $value)) {
                        Log::warning('Suspicious input detected in author form', [
                            'field' => $key,
                            'value' => $value,
                            'pattern' => $pattern,
                            'user_id' => auth()->id(),
                            'ip' => $request->ip()
                        ]);

                        abort(422, 'Contenu suspect détecté dans le formulaire.');
                    }
                }
            }
        }
    }

    /**
     * Validate photo upload security
     */
    private function validatePhotoSecurity(Request $request): void
    {
        $photo = $request->file('photo');
        
        if ($photo) {
            // Check file extension against MIME type
            $allowedMimes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            
            $mimeType = $photo->getMimeType();
            $extension = strtolower($photo->getClientOriginalExtension());
            
            if (!in_array($mimeType, $allowedMimes) || !in_array($extension, $allowedExtensions)) {
                Log::warning('Invalid file type upload attempted', [
                    'mime_type' => $mimeType,
                    'extension' => $extension,
                    'original_name' => $photo->getClientOriginalName(),
                    'user_id' => auth()->id(),
                    'ip' => $request->ip()
                ]);
                
                abort(422, 'Type de fichier non autorisé.');
            }

            // Check for embedded malicious content in image
            $fileContents = file_get_contents($photo->getPathname());
            $suspiciousStrings = ['<?php', '<script', 'javascript:', 'eval(', 'exec('];
            
            foreach ($suspiciousStrings as $suspicious) {
                if (strpos($fileContents, $suspicious) !== false) {
                    Log::warning('Malicious content detected in uploaded image', [
                        'original_name' => $photo->getClientOriginalName(),
                        'suspicious_content' => $suspicious,
                        'user_id' => auth()->id(),
                        'ip' => $request->ip()
                    ]);
                    
                    abort(422, 'Contenu malveillant détecté dans le fichier.');
                }
            }
        }
    }
}
