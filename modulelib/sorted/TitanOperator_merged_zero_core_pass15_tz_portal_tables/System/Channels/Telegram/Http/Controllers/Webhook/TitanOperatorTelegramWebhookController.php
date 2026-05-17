<?php

namespace App\Extensions\TitanOperator\System\Channels\Telegram\Http\Controllers\Webhook;

use App\Extensions\TitanOperator\System\Models\TitanOperator;
use App\Extensions\TitanOperator\System\Models\TitanOperatorChannelWebhook;
use App\Extensions\TitanOperator\System\Channels\Telegram\Services\Telegram\TelegramConversationService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TitanOperatorTelegramWebhookController extends Controller
{
    public function __construct(
        public TelegramConversationService $service
    ) {}

    public function handle(
        int $operatorId,
        int $channelId,
        Request $request
    ) {

        if (! $request->get('update_id') && ! $request->get('message')) {
            return [
                'status' => false,
            ];
        }

        TitanOperatorChannelWebhook::query()->create([
            'operator_id'         => $operatorId,
            'operator_channel_id' => $channelId,
            'payload'            => $request->all(),
            'created_at'         => now(),
        ]);

        $this->service
            ->setIpAddress()
            ->setTitanOperatorId($operatorId)
            ->setChannelId($channelId)
            ->setPayload($request->all());

        $conversation = $this->service->storeConversation();

        if ($conversation === null) {
            return;
        }

        /** @var TitanOperator $titan_operator */
        $titan_operator = $this->service->getTitanOperator();

        $this->service->insertMessage(
            conversation: $conversation,
            message: $request->input('message.text') ?? '',
            role: 'user',
            model: $titan_operator->getAttribute('ai_model')
        );

        $this->service->handleTelegram();
    }
}
