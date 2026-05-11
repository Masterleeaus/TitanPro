<?php

use Illuminate\Support\Facades\Route;
use App\Extensions\ProductPhotography\Controllers\QuoteMakerController;

Route::prefix('quotemaker')->name('dashboard.user.quotemaker.')->group(function () {
    Route::get('/', [QuoteMakerController::class, 'index'])->name('index');
    Route::post('/generate', [QuoteMakerController::class, 'generate'])->name('generate');
    Route::get('/gallery', [QuoteMakerController::class, 'gallery'])->name('gallery');
});
