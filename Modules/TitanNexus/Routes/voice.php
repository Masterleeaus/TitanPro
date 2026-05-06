<?php

use Illuminate\Support\Facades\Route;
use Modules\TitanNexus\Http\Controllers\Voice\TwilioWebhookBridgeController;

Route::post('/voice/twilio/webhook', TwilioWebhookBridgeController::class)->name('titannexus.voice.twilio.webhook');
