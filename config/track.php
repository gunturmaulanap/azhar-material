<?php

return [
    // Token untuk autentikasi dari server.js (Node)
    'api_token' => env('TRACK_API_TOKEN', ''),

    // (opsional) rate limit khusus tracking
    'rate_limit' => 120,

    // (opsional) origins yang diizinkan untuk snapshot kalau mau diproteksi
    'allowed_origins' => explode(',', env('TRACK_ALLOWED_ORIGINS', '')),
];
