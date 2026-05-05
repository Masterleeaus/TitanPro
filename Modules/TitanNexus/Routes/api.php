<?php
use Illuminate\Support\Facades\Route;
Route::prefix('titan-nexus')->group(function () {
    Route::post('agent/run', \Modules\TitanNexus\Http\Controllers\MarketingAgentController::class);
    Route::post('invoices/followup', \Modules\TitanNexus\Http\Controllers\InvoiceController::class);
    Route::post('payments/link', \Modules\TitanNexus\Http\Controllers\PaymentController::class);
    Route::post('jobs/assist', \Modules\TitanNexus\Http\Controllers\JobAssistController::class);
});
