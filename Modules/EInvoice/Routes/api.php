<?php

use Illuminate\Support\Facades\Route;
use Modules\EInvoice\Actions\GenerateLateInvoiceFollowupAction;
use Modules\EInvoice\Actions\PrepareZeroPayHandoffAction;
use Modules\EInvoice\Actions\SendInvoiceAction;
use Modules\EInvoice\Entities\Invoice;

Route::group(['middleware' => ['api', 'auth:sanctum'], 'prefix' => 'einvoice'], function () {
    Route::post('invoices/{invoice}/send', function (Invoice $invoice, SendInvoiceAction $action) {
        return response()->json($action->execute($invoice, request()->all()));
    })->name('einvoice.api.invoices.send');

    Route::post('invoices/{invoice}/zeropay-handoff', function (Invoice $invoice, PrepareZeroPayHandoffAction $action) {
        return response()->json($action->execute($invoice, request()->all()));
    })->name('einvoice.api.invoices.zeropay-handoff');

    Route::post('invoices/{invoice}/late-followup', function (Invoice $invoice, GenerateLateInvoiceFollowupAction $action) {
        $data = request()->validate(['days_overdue' => ['required', 'integer', 'min:0']]);
        return response()->json($action->execute($invoice, (int) $data['days_overdue']));
    })->name('einvoice.api.invoices.late-followup');
});
