<?php

use Illuminate\Support\Facades\Route;
use Modules\TitanTalk\Http\Controllers\DashboardController;
use Modules\TitanTalk\Http\Controllers\AIConverseController;

// Primary Titan Talk dashboard, used by sidebar/menu injections
Route::middleware(['web', 'auth'])
    ->prefix('account/titantalk')
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])
            ->name('titantalk.dashboard');
    });

// Legacy AIConverse endpoint (kept for backward compatibility / pings)
Route::middleware(['web'])
    ->prefix('aiconverse')
    ->group(function () {
        Route::get('/', [AIConverseController::class, 'index'])
            ->name('titantalk.index');
        Route::get('/ping', fn () => 'TitanTalk OK')
            ->name('titantalk.ping');
    });
