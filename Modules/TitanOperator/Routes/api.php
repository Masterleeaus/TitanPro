<?php

use Illuminate\Support\Facades\Route;
use Modules\TitanOperator\Http\Controllers\ChannelWebhookController;

Route::prefix('api/v2/titan_operator')
    ->name('api.v2.titan_operator.channel.')
    ->group(function (): void {
        Route::any('{operatorId}/channel/{channelId}/{channel}', ChannelWebhookController::class)
            ->whereIn('channel', ['whatsapp', 'telegram', 'messenger', 'web'])
            ->name('dispatch');

        Route::any('{operatorId}/channel/{channelId}/whatsapp', ChannelWebhookController::class)
            ->name('whatsapp.post.handle')
            ->defaults('channel', 'whatsapp');

        Route::any('{operatorId}/channel/{channelId}/telegram', ChannelWebhookController::class)
            ->name('telegram.post.handle')
            ->defaults('channel', 'telegram');

        Route::any('{operatorId}/channel/{channelId}/messenger', ChannelWebhookController::class)
            ->name('messenger.post.handle')
            ->defaults('channel', 'messenger');
    });
