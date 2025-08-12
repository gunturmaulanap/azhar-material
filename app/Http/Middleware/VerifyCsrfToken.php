<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    protected $except = [
        'api/auth/login',
        'api/auth/register',
        'api/auth/logout',
        // kalau nanti ada endpoint API lain yang POST dan memang tidak pakai session, tambahkan di sini
    ];
}
