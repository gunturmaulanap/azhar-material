<?php

return [

    'manifest' => public_path('build/.vite/manifest.json'),

    'hot_file' => public_path('hot'),

    'dev_server' => [
        'url' => 'http://localhost:5173',
    ],

    'build_directory' => 'build',

    'asset_url' => env('ASSET_URL', null),

];
