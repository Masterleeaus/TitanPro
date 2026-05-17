<?php

use Illuminate\Support\Facades\Route;

Route::middleware(config('groundzeroops.routes.web_middleware', ['web', 'auth']))
    ->prefix('groundzero-ops')
    ->name('groundzeroops.web.')
    ->group(function () {
        Route::get('/health', fn () => response()->json(['ok' => true, 'module' => 'GroundZeroOps']))
            ->name('health');
    });
