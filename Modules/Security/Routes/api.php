<?php

use Illuminate\Support\Facades\Route;
use Modules\Security\Http\Controllers\API\SecurityModuleController;
use Modules\Security\Http\Controllers\API\CleanerOperationsController;
use Modules\Security\Http\Controllers\API\CleanerReportsController;

Route::prefix('security')
    ->middleware(['auth:api', 'throttle:security-api'])
    ->name('api.security.')
    ->group(function () {
        Route::get('dashboard', [SecurityModuleController::class, 'dashboard'])->name('dashboard');
        Route::get('health', [SecurityModuleController::class, 'health'])->name('health');
        Route::get('status', [SecurityModuleController::class, 'status'])->name('status');
        Route::get('features', [SecurityModuleController::class, 'features'])->name('features');
        Route::get('permissions', [SecurityModuleController::class, 'permissions'])->name('permissions');
        Route::get('diagnostics', [SecurityModuleController::class, 'diagnostics'])->name('diagnostics');
        Route::get('readiness', [SecurityModuleController::class, 'readiness'])->name('readiness');

        Route::get('cleaners/dashboard', [CleanerOperationsController::class, 'dashboard'])->name('cleaners.dashboard');
        Route::get('cleaner-sites', [CleanerOperationsController::class, 'sites'])->name('cleaner_sites.index');
        Route::post('cleaner-sites', [CleanerOperationsController::class, 'storeSite'])->name('cleaner_sites.store');
        Route::get('cleaners', [CleanerOperationsController::class, 'index'])->name('cleaners.index');
        Route::post('cleaners', [CleanerOperationsController::class, 'store'])->name('cleaners.store');
        Route::get('cleaners/{cleaner}', [CleanerOperationsController::class, 'show'])->name('cleaners.show');
        Route::post('cleaners/{cleaner}/approve', [CleanerOperationsController::class, 'approve'])->name('cleaners.approve');
        Route::post('cleaners/{cleaner}/decision', [CleanerOperationsController::class, 'decide'])->name('cleaners.decision');
        Route::post('cleaners/{cleaner}/check-in', [CleanerOperationsController::class, 'checkIn'])->name('cleaners.check_in');
        Route::post('cleaners/{cleaner}/check-out', [CleanerOperationsController::class, 'checkOut'])->name('cleaners.check_out');
        Route::post('cleaners/{cleaner}/force-check-out', [CleanerOperationsController::class, 'forceCheckOut'])->name('cleaners.force_check_out');

        Route::get('cleaner-reports/daily', [CleanerReportsController::class, 'daily'])->name('cleaner_reports.daily');
        Route::get('cleaner-reports/sites', [CleanerReportsController::class, 'sites'])->name('cleaner_reports.sites');
        Route::get('cleaner-reports/exceptions', [CleanerReportsController::class, 'exceptions'])->name('cleaner_reports.exceptions');
    });

// Legacy compatibility endpoints. These keep old API paths reachable while
// removing duplicated /guestbooks route declarations from the merged modules.
Route::middleware(['auth:api', 'throttle:security-api'])->group(function () {
    Route::get('/suppliers', fn () => request()->user())->name('api.security.legacy.suppliers');
    Route::get('/guestbooks', fn () => request()->user())->name('api.security.legacy.guestbooks');
    Route::get('/cardakses', fn () => request()->user())->name('api.security.legacy.cardakses');
});
