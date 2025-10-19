<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetSecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Content Security Policy - Balanced approach for Laravel compatibility
        $response->headers->set('Content-Security-Policy',
            "default-src 'self'; " .
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://unpkg.com https://code.jquery.com; " .
            "style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://unpkg.com https://fonts.googleapis.com; " .
            "img-src 'self' data: https://cdn.jsdelivr.net https://unpkg.com https://code.jquery.com https://fonts.googleapis.com https://fonts.gstatic.com https://dev4ult.github.io https://*.tile.openstreetmap.org blob:; " .
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

        // Permissions-Policy
        $response->headers->set('Permissions-Policy',
            'geolocation=(), microphone=(), camera=()'
        );

        return $response;
    }
}
