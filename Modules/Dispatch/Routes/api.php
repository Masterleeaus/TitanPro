<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Dispatch\Http\Controllers\API\DispatchApiController;

Route::prefix('v1')->name('dispatch.api.')->group(function (): void {
    Route::get('work-orders', [DispatchApiController::class, 'index'])->name('work-orders.index');
    Route::post('work-orders', [DispatchApiController::class, 'store'])->name('work-orders.store');
    Route::get('work-orders/{workOrder}', [DispatchApiController::class, 'show'])->name('work-orders.show');
    Route::get('calendar', [DispatchApiController::class, 'calendar'])->name('calendar');
    Route::get('kpis', [DispatchApiController::class, 'kpis'])->name('kpis');
    Route::post('recommend-technicians', [DispatchApiController::class, 'recommendations'])->name('recommend-technicians');
    Route::post('schedule', [DispatchApiController::class, 'schedule'])->name('schedule');
    Route::patch('appointments/{appointment}/reschedule', [DispatchApiController::class, 'rescheduleAppointment'])->name('appointments.reschedule');
    Route::patch('assignments/{assignment}/status', [DispatchApiController::class, 'updateStatus'])->name('assignments.status');
    Route::post('routes/build', [DispatchApiController::class, 'buildRoute'])->name('routes.build');
    Route::post('routes/{route}/resequence', [DispatchApiController::class, 'resequenceRoute'])->name('routes.resequence');
});
