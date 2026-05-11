<?php

namespace App\Extensions\TitanOperator\System\Channels\Whatsapp\Http\Controllers\Webhook;

use App\Extensions\TitanOperator\System\Models\TitanOperatorChannelWebhook;
use App\Extensions\TitanOperator\System\Channels\Whatsapp\Services\Twillio\TwilioConversationService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TitanOperatorTwilioController extends Controller
{
    public function __construct(
        public TwilioConversationService $service
    ) {}

    public function handle(
        int $operatorId,
        int $channelId,
        Request $request
    ) {
        if (! $request->get('SmsSid')) {
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

        $titan_operator = $this->service->getTitanOperator();

        $this->service->insertMessage(
            conversation: $conversation,
            message: $request->get('Body') ?? '',
            role: 'user',
            model: $titan_operator->getAttribute('ai_model')
        );

        $this->service->handleWhatsapp();
    }
}
