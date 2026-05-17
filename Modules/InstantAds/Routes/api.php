<?php

use Illuminate\Support\Facades\Route;
use Modules\InstantAds\Http\Controllers\AdminInstantAdsController;
use Modules\InstantAds\Http\Controllers\InstantAdsController;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/generate', [InstantAdsController::class, 'generate'])->name('generate');
    Route::post('/batch-variants', [InstantAdsController::class, 'createBatchVariants'])->name('batch-variants');
    Route::post('/settings', [AdminInstantAdsController::class, 'updateSettings'])->name('settings');
});
