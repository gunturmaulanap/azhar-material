<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Ke mana dialihkan jika belum autentik.
     */
    protected function redirectTo(Request $request): ?string
    {
        // Untuk SPA/API, biarkan 401 JSON (tanpa redirect)
        if ($request->expectsJson() || $request->is('api/*')) {
            return null;
        }

        // Semua rute web dialihkan ke halaman login SPA
        return route('login');
    }
}
