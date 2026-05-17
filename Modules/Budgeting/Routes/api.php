<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Budgeting\Http\Controllers\API\BudgetActualController;
use Modules\Budgeting\Http\Controllers\API\ExpenseController;
use Modules\Budgeting\Http\Controllers\API\ForecastScenarioController;
use Modules\Budgeting\Http\Controllers\API\KpiDashboardController;
use Modules\Budgeting\Http\Controllers\API\ReceiptController;
use Modules\Budgeting\Http\Controllers\API\ReimbursementController;
use Modules\Budgeting\Http\Controllers\API\VarianceController;

Route::prefix('api/v1/budgeting')
    ->middleware(['api', 'auth:sanctum'])
    ->group(function (): void {
        Route::apiResource('expenses', ExpenseController::class);
        Route::post('expenses/{expense}/approve', [ExpenseController::class, 'approve']);
        Route::post('expenses/{expense}/reject', [ExpenseController::class, 'reject']);

        Route::apiResource('receipts', ReceiptController::class)->except(['update']);

        Route::apiResource('reimbursements', ReimbursementController::class)->except(['update', 'destroy']);
        Route::post('reimbursements/{reimbursementBatch}/approve', [ReimbursementController::class, 'approve']);
        Route::post('reimbursements/{reimbursementBatch}/mark-paid', [ReimbursementController::class, 'markPaid']);

        Route::apiResource('actuals', BudgetActualController::class)->only(['index', 'store', 'show']);

        Route::apiResource('variances', VarianceController::class)->only(['index', 'show']);

        Route::apiResource('forecast-scenarios', ForecastScenarioController::class)->except(['update', 'destroy']);
        Route::post('forecast-scenarios/{forecastScenario}/run', [ForecastScenarioController::class, 'run']);

        Route::get('kpi-snapshots', [KpiDashboardController::class, 'index']);
    });
