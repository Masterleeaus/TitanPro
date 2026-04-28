<?php

use App\Http\Controllers\EsoftTemplateController;
use Illuminate\Support\Facades\Route;

Route::prefix('templates/esoft')
    ->name('esoft.')
    ->group(function () {
        Route::get('/', [EsoftTemplateController::class, 'root'])->name('root');
        Route::get('{first}/{second}/{third}', [EsoftTemplateController::class, 'thirdLevel'])->name('third');
        Route::get('{first}/{second}', [EsoftTemplateController::class, 'secondLevel'])->name('second');
        Route::get('{any}', [EsoftTemplateController::class, 'firstLevel'])->name('any');
    });
