<?php

use Illuminate\Support\Facades\Route;
use App\Extensions\TitanLeads\System\Http\Controllers\Webhook\MessengerWebhookController;

Route::get('/webhooks/messenger', [MessengerWebhookController::class, 'verify']);
Route::post('/webhooks/messenger', [MessengerWebhookController::class, 'inbound']);
