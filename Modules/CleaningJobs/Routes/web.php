<?php

use Illuminate\Support\Facades\Route;
use Modules\CleaningJobs\Http\Controllers\SettingsController;
use Modules\CleaningJobs\Http\Controllers\WorkOrderController;
use Modules\CleaningJobs\Http\Controllers\WOTypeController;
use Modules\CleaningJobs\Http\Controllers\WORequestController;
use Modules\CleaningJobs\Http\Controllers\WOServiceAppointmentController;
use Modules\CleaningJobs\Http\Controllers\WOServiceTaskController;
use Modules\CleaningJobs\Http\Controllers\WOServicePartController;
use Modules\CleaningJobs\Http\Controllers\ServiceTaskController;
use Modules\CleaningJobs\Http\Controllers\ServicePartController;

$prefix = config('cleaningjobs.routes.prefix', config('titanwork.routes.prefix', 'cleaning-jobs'));
$middleware = config('cleaningjobs.routes.middleware', config('titanwork.routes.middleware', ['web', 'auth']));

Route::middleware($middleware)
    ->prefix($prefix)
    ->as('cleaningjobs.')
    ->group(function () {
        Route::view('/', 'cleaningjobs::control-panel')->name('index')->middleware('permission:cleaningjobs.view');

        Route::post('orders/{id}/convert-to-project', [WorkOrderController::class, 'convertToProject'])
            ->middleware('permission:cleaningjobs.update')
            ->name('orders.convert');

        Route::resource('orders', WorkOrderController::class)
            ->only(['index', 'show'])
            ->middleware('permission:cleaningjobs.view');
        Route::resource('orders', WorkOrderController::class)
            ->only(['create', 'store'])
            ->middleware('permission:cleaningjobs.create');
        Route::resource('orders', WorkOrderController::class)
            ->only(['edit', 'update'])
            ->middleware('permission:cleaningjobs.update');
        Route::resource('orders', WorkOrderController::class)
            ->only(['destroy'])
            ->middleware('permission:cleaningjobs.delete');

        Route::resource('types', WOTypeController::class)->middleware('permission:cleaningjobs.types.manage');
        Route::resource('requests', WORequestController::class)->middleware('permission:cleaningjobs.requests.manage');
        Route::resource('appointments', WOServiceAppointmentController::class)->middleware('permission:cleaningjobs.appointments.manage');
        Route::resource('tasks', WOServiceTaskController::class)->middleware('permission:cleaningjobs.tasks.manage');
        Route::resource('parts', WOServicePartController::class)->middleware('permission:cleaningjobs.parts.manage');
        Route::resource('service-tasks', ServiceTaskController::class)->middleware('permission:cleaningjobs.tasks.manage');
        Route::resource('service-parts', ServicePartController::class)->middleware('permission:cleaningjobs.parts.manage');
    });

Route::middleware(['web', 'can:cleaningjobs.settings'])
    ->prefix('admin/cleaning-jobs')
    ->as('cleaningjobs.')
    ->group(function () {
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');
    });
