<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo(Request $request): ?string
    {
        // Jika permintaan mengharapkan JSON (misalnya dari API call React),
        // jangan redirect. Biarkan Laravel mengembalikan 401 Unauthorized.
        // Ini sudah benar.
        if ($request->expectsJson()) {
            return null;
        }

        // Jika permintaan adalah untuk rute web superadmin, admin, master, owner, atau content
        // dan belum terautentikasi, arahkan ke rute login React.
        // Pastikan ini juga berlaku untuk rute API yang diproteksi dari frontend React.
        // Jika permintaan bukan AJAX dan tidak terautentikasi, redirect ke /login
        if ($request->is('superadmin/*') || $request->is('admin/*') || $request->is('master/*') || $request->is('owner/*') || $request->is('content/*')) {
            return route('login'); // Mengarahkan ke rute web yang memuat React.
        }

        // Untuk semua rute lain yang memerlukan autentikasi, arahkan ke halaman login React.
        return route('login'); // Mengarahkan ke rute web yang memuat React.
    }
}
