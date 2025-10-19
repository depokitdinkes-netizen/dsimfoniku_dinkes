<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetSecurityHeadersStrict
{
    /**
     * STRICT CSP - Test this version to see if your app works without unsafe-eval
     * If your app breaks, use SetSecurityHeaders instead
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Generate nonce for inline scripts and styles
        $nonce = base64_encode(random_bytes(16));
        $request->attributes->set('csp_nonce', $nonce);

        // STRICT Content Security Policy - WITHOUT unsafe-eval
        $response->headers->set('Content-Security-Policy',
            "default-src 'self'; " .
            "script-src 'self' 'nonce-{$nonce}' 'unsafe-inline' https://cdn.jsdelivr.net https://unpkg.com https://code.jquery.com; " .
            "style-src 'self' 'nonce-{$nonce}' 'unsafe-inline' https://cdn.jsdelivr.net https://unpkg.com https://fonts.googleapis.com; " .
            "img-src 'self' data: https://cdn.jsdelivr.net https://unpkg.com https://code.jquery.com https://fonts.googleapis.com https://fonts.gstatic.com https://dev4ult.github.io blob:; " .
            "font-src 'self' data: https://fonts.gstatic.com https://cdn.jsdelivr.net; " .
            "connect-src 'self' https://cdn.jsdelivr.net https://unpkg.com https://dev4ult.github.io; " .
            "frame-ancestors 'self'; " .
            "base-uri 'self'; " .
            "form-action 'self'; " .
            "upgrade-insecure-requests;"
        );

        // X-Content-Type-Options
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // X-Frame-Options
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // X-XSS-Protection
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Referrer-Policy
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Permissions-Policy - Allow geolocation for same origin
        $response->headers->set('Permissions-Policy',
            'geolocation=(self), microphone=(), camera=()'
        );

        return $response;
    }
}
