<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Illuminate\Http\Request;

class TrustProxies extends Middleware
{
    /**
     * Trust all reverse proxies (Cloudflare/NGINX/cPanel).
     * If you want to be stricter, put a comma‑separated list in TRUSTED_PROXIES.
     */
    protected $proxies = null; // default
    // Better: read from env, fallback to "*"
    // protected $proxies = explode(',', (string) env('TRUSTED_PROXIES', '*'));

    /**
     * Let Laravel use standard X-Forwarded-* headers to detect HTTPS/host/port.
     */
    protected $headers =
    Request::HEADER_X_FORWARDED_FOR |
        Request::HEADER_X_FORWARDED_HOST |
        Request::HEADER_X_FORWARDED_PORT |
        Request::HEADER_X_FORWARDED_PROTO |
        Request::HEADER_X_FORWARDED_AWS_ELB;
}
