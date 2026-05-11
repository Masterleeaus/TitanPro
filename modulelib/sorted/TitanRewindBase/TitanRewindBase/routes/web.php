<?php

use Illuminate\Support\Facades\Route;
use App\Extensions\TitanRewind\System\Http\Controllers\TitanRewindCaseController;

Route::middleware(['web', 'auth'])
    ->prefix('dashboard/user/titanrewind')
    ->name('titanrewind.')
    ->group(function () {
        Route::get('/', [TitanRewindCaseController::class, 'index'])->name('cases.index');
        Route::get('/cases/{case}', [TitanRewindCaseController::class, 'show'])->name('cases.show');
        Route::post('/cases/{case}/propose-fix', [TitanRewindCaseController::class, 'proposeFix'])->name('cases.proposeFix');
        Route::post('/cases/{case}/apply-fix', [TitanRewindCaseController::class, 'applyFix'])->name('cases.applyFix');
        Route::post('/cases/{case}/resolve', [TitanRewindCaseController::class, 'resolve'])->name('cases.resolve');
    });
