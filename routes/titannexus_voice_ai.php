<?php

use App\Http\Controllers\TitanNexus\VoiceAiWebhookController;
use Illuminate\Support\Facades\Route;

Route::prefix('titan-nexus/voice/webhooks')->group(function (): void {
    Route::post('bland', [VoiceAiWebhookController::class, 'bland'])->name('titannexus.voice.webhooks.bland');
    Route::post('vapi/inbound', [VoiceAiWebhookController::class, 'vapiInbound'])->name('titannexus.voice.webhooks.vapi-inbound');
    Route::post('vapi/outbound', [VoiceAiWebhookController::class, 'vapiOutbound'])->name('titannexus.voice.webhooks.vapi-outbound');
});
