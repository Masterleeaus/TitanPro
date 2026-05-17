<?php

use Illuminate\Support\Facades\Route;
use Modules\TitanGoField\Http\Controllers\Api\FieldJobApiController;
use Modules\TitanGoField\Http\Controllers\Api\SignoffController;
use Modules\TitanGoField\Http\Controllers\Api\AIAssistantProxyController;
use Modules\TitanGoField\Http\Controllers\Api\AIToolProxyController;

Route::middleware(['api', 'auth:sanctum'])->prefix('titango/field')->name('titango_field.api.')->group(function () {

    Route::get('/health', fn () => response()->json(['ok' => true, 'module' => 'TitanGoField']))->name('health');

    Route::apiResource('jobs', FieldJobApiController::class);
    Route::post('jobs/{job}/status', [FieldJobApiController::class, 'updateStatus'])->name('jobs.status');
    Route::post('jobs/{job}/checkin', [FieldJobApiController::class, 'checkIn'])->name('jobs.checkin');
    Route::post('jobs/{job}/checkout', [FieldJobApiController::class, 'checkOut'])->name('jobs.checkout');
    Route::post('jobs/{job}/complete', [FieldJobApiController::class, 'complete'])->name('jobs.complete');

    Route::post('jobs/{job}/signoff', [SignoffController::class, 'store'])->name('jobs.signoff');

    Route::post('ai/assistant', [AIAssistantProxyController::class, 'handle'])->name('ai.assistant');
    Route::post('ai/tools', [AIToolProxyController::class, 'dispatch'])->name('ai.tools');
});
