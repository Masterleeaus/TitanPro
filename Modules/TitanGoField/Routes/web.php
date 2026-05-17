<?php

use Illuminate\Support\Facades\Route;
use Modules\TitanGoField\Http\Controllers\FieldJobController;
use Modules\TitanGoField\Http\Controllers\ChecklistController;
use Modules\TitanGoField\Http\Controllers\RecurrenceController;
use Modules\TitanGoField\Http\Controllers\DashboardController;
use Modules\TitanGoField\Http\Controllers\ReportsController;
use Modules\TitanGoField\Http\Controllers\SettingsController;
use Modules\TitanGoField\Http\Controllers\ClientPortalController;
use Modules\TitanGoField\Http\Controllers\ClientSignController;
use Modules\TitanGoField\Http\Controllers\IcsExportController;

Route::middleware(['web', 'auth'])->prefix('titango/field')->name('titango_field.')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('jobs', FieldJobController::class);
    Route::post('jobs/{job}/status', [FieldJobController::class, 'updateStatus'])->name('jobs.status');
    Route::post('jobs/{job}/convert-to-project', [FieldJobController::class, 'convertToProject'])->name('jobs.convert');
    Route::get('jobs/{job}/ics', [IcsExportController::class, 'export'])->name('jobs.ics');

    Route::prefix('jobs/{job}')->name('jobs.')->group(function () {
        Route::resource('checklists', ChecklistController::class)->shallow();
        Route::resource('recurrences', RecurrenceController::class)->shallow();
    });

    Route::get('/reports', [ReportsController::class, 'index'])->name('reports.index');
    Route::get('/reports/revenue', [ReportsController::class, 'revenue'])->name('reports.revenue');
    Route::get('/reports/compliance', [ReportsController::class, 'compliance'])->name('reports.compliance');
    Route::get('/reports/timeline', [ReportsController::class, 'timeline'])->name('reports.timeline');
    Route::get('/reports/audit', [ReportsController::class, 'audit'])->name('reports.audit');

    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
});

// Client portal — unauthenticated, token-guarded
Route::middleware(['web'])->prefix('field-jobs/portal')->name('titango_field.portal.')->group(function () {
    Route::get('{token}', [ClientPortalController::class, 'show'])->name('show');
    Route::get('{token}/sign', [ClientSignController::class, 'show'])->name('sign.show');
    Route::post('{token}/sign', [ClientSignController::class, 'store'])->name('sign.store');
});
