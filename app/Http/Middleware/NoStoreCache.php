<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NoStoreCache
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Response $response */
        $response = $next($request);
        $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', '0');
        $existingVary = $response->headers->get('Vary');
        $varyValues = array_filter(array_map('trim', explode(',', (string) $existingVary)));
        foreach (['Cookie', 'Authorization'] as $value) {
            if (!in_array($value, $varyValues, true)) {
                $varyValues[] = $value;
            }
        }
        if (!empty($varyValues)) {
            $response->headers->set('Vary', implode(', ', $varyValues));
        }
        return $response;
    }
}