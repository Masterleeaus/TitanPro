<?php

use Illuminate\Support\Facades\Route;
use Modules\CleaningJobs\Http\Controllers\Api\WorkOrderApiController;

Route::middleware(config('cleaningjobs.routes.api_middleware', ['api', 'auth:sanctum']))
    ->prefix('cleaning-jobs')
    ->as('api.cleaningjobs.')
    ->group(function () {
        Route::get('/health', fn () => response()->json(['ok' => true, 'module' => 'CleaningJobs']))->withoutMiddleware(['auth:sanctum']);
        Route::apiResource('orders', WorkOrderApiController::class);
    });

Route::prefix('cleaning-jobs')->name('cleaningjobs.')->group(function () {
    Route::apiResource('tasks', \Modules\CleaningJobs\Http\Controllers\JobTaskController::class)->except(['create','edit','show']);
    Route::post('tasks/{task}/move', [\Modules\CleaningJobs\Http\Controllers\JobTaskController::class, 'move'])->name('tasks.move');
    Route::apiResource('timesheets', \Modules\CleaningJobs\Http\Controllers\JobTimesheetController::class)->except(['create','edit','show','destroy']);
    Route::post('timesheets/{timesheet}/approve', [\Modules\CleaningJobs\Http\Controllers\JobTimesheetController::class, 'approve'])->name('timesheets.approve');
    Route::get('resources/allocations', [\Modules\CleaningJobs\Http\Controllers\JobResourceController::class, 'allocations'])->name('resources.allocations');
    Route::post('resources/allocations', [\Modules\CleaningJobs\Http\Controllers\JobResourceController::class, 'storeAllocation'])->name('resources.allocations.store');
    Route::get('resources/capacity', [\Modules\CleaningJobs\Http\Controllers\JobResourceController::class, 'capacity'])->name('resources.capacity');
});

Route::get('cleaning-jobs/capabilities', [\Modules\CleaningJobs\Http\Controllers\UnifiedJobManagementController::class, 'capabilities'])->name('cleaning-jobs.capabilities');
