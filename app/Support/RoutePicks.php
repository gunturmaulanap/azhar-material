<?php

namespace App\Support;

use Illuminate\Support\Facades\Route;

trait RoutePicks
{
    private function pickFirstExistingRoute(array $candidates): ?string
    {
        foreach ($candidates as $name) {
            if (Route::has($name)) {
                return $name;
            }
        }
        return null;
    }
}
