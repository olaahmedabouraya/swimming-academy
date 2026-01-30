<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceJsonResponse
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only apply to API routes
        if ($request->is('api/*')) {
            // Force JSON accept header
            $request->headers->set('Accept', 'application/json');
            
            // Get the response
            $response = $next($request);
            
            // Ensure response is JSON
            if (!$response->headers->has('Content-Type') || 
                !str_contains($response->headers->get('Content-Type'), 'application/json')) {
                $response->headers->set('Content-Type', 'application/json');
            }
            
            // Security and cache headers
            $response->headers->set('X-Content-Type-Options', 'nosniff');
            $response->headers->set('X-Frame-Options', 'DENY');
            $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate');
            $response->headers->set('Pragma', 'no-cache');
            $response->headers->set('Expires', '0');
            
            return $response;
        }
        
        return $next($request);
    }
}

