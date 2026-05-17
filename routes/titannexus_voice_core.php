<?php

use App\Http\Controllers\TitanNexus\VoiceCoreWebhookController;
use Illuminate\Support\Facades\Route;

Route::prefix('titan-nexus/voice-core')->group(function (): void {
    Route::post('webhooks/twilio', [VoiceCoreWebhookController::class, 'twilio'])->name('titannexus.voice-core.webhooks.twilio');
    Route::post('webhooks/generic', [VoiceCoreWebhookController::class, 'generic'])->name('titannexus.voice-core.webhooks.generic');
});
