<?php

use Illuminate\Support\Facades\Route;
use Modules\GroundZeroOps\Http\Controllers\Api\DispatchController;
use Modules\GroundZeroOps\Http\Controllers\Api\IncidentController;
use Modules\GroundZeroOps\Http\Controllers\Api\ShiftController;

Route::middleware(config('groundzeroops.routes.api_middleware', ['api', 'auth:sanctum']))
    ->prefix('groundzero-ops')
    ->name('groundzeroops.api.')
    ->group(function () {
        Route::post('/dispatch/assign', [DispatchController::class, 'assign'])->name('dispatch.assign');
        Route::post('/shifts/start', [ShiftController::class, 'start'])->name('shifts.start');
        Route::post('/shifts/{shift}/end', [ShiftController::class, 'end'])->name('shifts.end');
        Route::post('/incidents', [IncidentController::class, 'store'])->name('incidents.store');
    });
