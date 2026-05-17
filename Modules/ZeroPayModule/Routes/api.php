<?php

use Illuminate\Support\Facades\Route;
use Modules\ZeroPayModule\Http\Controllers\Api\NotificationApiController;
use Modules\ZeroPayModule\Http\Controllers\Api\WalletApiController;
use Modules\ZeroPayModule\Http\Controllers\Api\ZeroPayPushController;
use Modules\ZeroPayModule\Http\Controllers\Api\ZeroPaySessionController;
use Modules\ZeroPayModule\Http\Controllers\Api\ZeroPayTransactionController;
use Modules\ZeroPayModule\Http\Controllers\Api\ZeroPayWebhookController;

Route::get('/status', fn () => response()->json(['module' => 'ZeroPayModule', 'status' => 'active']))->name('status');

Route::middleware(['auth:sanctum', 'throttle:zeropay-api'])->group(function () {
    // Sessions
    Route::post('sessions', [ZeroPaySessionController::class, 'store'])->name('sessions.store');
    Route::get('sessions/{session}', [ZeroPaySessionController::class, 'show'])->name('sessions.show');
    Route::patch('sessions/{session}/pay', [ZeroPaySessionController::class, 'pay'])->name('sessions.pay');
    Route::post('sessions/{session}/complete', [ZeroPaySessionController::class, 'complete'])->name('sessions.complete');
    Route::post('sessions/{session}/cancel', [ZeroPaySessionController::class, 'cancel'])->name('sessions.cancel');

    // Transactions
    Route::get('transactions', [ZeroPayTransactionController::class, 'index'])->name('transactions.index');
    Route::get('transactions/{id}', [ZeroPayTransactionController::class, 'show'])->name('transactions.show');

    // Wallet
    Route::get('wallet', [WalletApiController::class, 'index'])->name('wallet.index');
    Route::get('wallet/receive-info', [WalletApiController::class, 'receiveInfo'])->name('wallet.receive-info');

    // Push subscriptions
    Route::post('push/subscribe', [ZeroPayPushController::class, 'subscribe'])->name('push.subscribe');
    Route::delete('push/subscribe', [ZeroPayPushController::class, 'unsubscribe'])->name('push.unsubscribe');

    // Notifications
    Route::get('notifications', [NotificationApiController::class, 'index'])->name('notifications.index');
    Route::patch('notifications/{id}/read', [NotificationApiController::class, 'markRead'])->name('notifications.read');
});

// Webhook routes — unauthenticated, signature-verified by each gateway adapter, throttled at 10 req/min
Route::middleware('throttle:zeropay-webhooks')
    ->prefix('webhooks')
    ->name('webhooks.')
    ->group(function () {
        Route::post('stripe', [ZeroPayWebhookController::class, 'stripe'])->name('stripe');
        Route::post('paypal', [ZeroPayWebhookController::class, 'paypal'])->name('paypal');
        Route::post('cryptomus', [ZeroPayWebhookController::class, 'cryptomus'])->name('cryptomus');
        Route::post('bank', [ZeroPayWebhookController::class, 'bank'])->name('bank');
    });
