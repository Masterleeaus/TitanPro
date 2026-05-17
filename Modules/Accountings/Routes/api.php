<?php

use Illuminate\Support\Facades\Route;
use Modules\Accountings\Actions\LookupLedgerAction;
use Modules\Accountings\Actions\PostInvoiceJournalAction;

Route::group(['middleware' => ['api', 'auth:sanctum'], 'prefix' => 'accountings'], function () {
    Route::get('ledger', function (LookupLedgerAction $action) {
        $data = request()->validate([
            'limit' => ['nullable', 'integer', 'min:1', 'max:200'],
        ]);

        return response()->json($action->execute($data));
    })->name('accountings.api.ledger');

    Route::post('invoice-journals', function (PostInvoiceJournalAction $action) {
        $data = request()->validate([
            'invoice_id' => ['nullable'],
            'amount' => ['nullable', 'numeric'],
            'reference' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'date' => ['nullable', 'date'],
        ]);

        return response()->json($action->execute($data));
    })->name('accountings.api.invoice-journals.store');

    Route::get('gst', fn () => response()->json(['status' => 'available', 'owner' => 'titan_money_accounting']))
        ->name('accountings.api.gst');
});
