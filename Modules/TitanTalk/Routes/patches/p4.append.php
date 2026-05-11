<?php
use Illuminate\Support\Facades\Route;
use Modules\TitanTalk\Http\Controllers\ConverseController;
use Modules\TitanTalk\Http\Controllers\WebhookController;

// Apply PolicyGuard to API send + hooks
Route::middleware(['api', \Modules\TitanTalk\Http\Middleware\PolicyGuard::class])->prefix('aiconverse')->group(function () {
    Route::post('/send', [ConverseController::class, 'send'])->name('titantalk.api.send'); // ensure exists
    Route::post('/hook/{driver}', [WebhookController::class, 'receive'])->name('titantalk.webhook.receive'); // ensure exists
});


