<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeadersMiddleware
{
    /**
     * Handle an incoming request and apply ISO 27001 / OWASP Security Headers.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // 1. Prevent Clickjacking
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // 2. Prevent MIME-Type Sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // 3. Referrer Privacy Policy
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // 4. Permissions-Policy (Disable unused device APIs)
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=(), payment=()');

        // 5. Cross-Site Scripting Protection (Legacy & Modern CSP)
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // 6. Content Security Policy (Allow trusted Three.js CDN, Tailwind CDN, Google Fonts, Unsplash, Blob URLs & Workers for GLTF/Draco, CKEditor CDN, Midtrans Snap)
        $csp = "default-src 'self' data: blob:; "
            . "script-src 'self' 'unsafe-inline' 'unsafe-eval' blob: https://cdn.tailwindcss.com https://cdnjs.cloudflare.com https://cdn.jsdelivr.net https://cdn.ckeditor.com https://app.sandbox.midtrans.com https://app.midtrans.com; "
            . "worker-src 'self' blob:; "
            . "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.ckeditor.com https://app.sandbox.midtrans.com https://app.midtrans.com; "
            . "font-src 'self' https://fonts.gstatic.com data:; "
            . "img-src 'self' data: blob: https://images.unsplash.com https://raw.githubusercontent.com https://cdn.ckeditor.com https://app.sandbox.midtrans.com https://app.midtrans.com; "
            . "frame-src 'self' https://app.sandbox.midtrans.com https://app.midtrans.com; "
            . "connect-src 'self' blob: data: https://cdnjs.cloudflare.com https://cdn.jsdelivr.net https://cdn.ckeditor.com https://app.sandbox.midtrans.com https://app.midtrans.com;";

        $response->headers->set('Content-Security-Policy', $csp);

        return $response;
    }
}
