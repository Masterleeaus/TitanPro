<?php

namespace App\Extensions\TitanOperator\System\Channels\Messenger\Http\Controllers\Webhook;

use App\Extensions\TitanOperator\System\Models\TitanOperatorChannel;
use App\Extensions\TitanOperator\System\Models\TitanOperatorChannelWebhook;
use App\Extensions\TitanOperator\System\Channels\Messenger\Services\MessengerConversationService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TitanOperatorMessengerWebhookController extends Controller
{
    public function __construct(
        public MessengerConversationService $service
    ) {}

    public function handle(
        int $operatorId,
        int $channelId,
        Request $request
    ) {
        $channel = TitanOperatorChannel::query()->findOrFail($channelId);

        TitanOperatorChannelWebhook::query()->create([
            'operator_id'         => $operatorId,
            'operator_channel_id' => $channelId,
            'payload'            => $request->all(),
            'created_at'         => now(),
        ]);

        $verify = $this->verifyWebhook(data_get($channel['credentials'], 'verify_token', ''));
        if ($verify) {
            return $verify;
        }

        if (! $request->input('entry.0.messaging.0')) {
            return response('OK', 200);
        }

        $this->service
            ->setIpAddress()
            ->setTitanOperatorId($operatorId)
            ->setChannelId($channelId)
            ->setPayload($request->input('entry.0.messaging.0'));

        $conversation = $this->service->storeConversation();

        /**
         * @var TitanOperatorChannel $titan_operator
         */
        $titan_operator = $this->service->getTitanOperator();

        $this->service->insertMessage(
            conversation: $conversation,
            message: $request->input('entry.0.messaging.0.message.text') ?? '',
            role: 'user',
            model: $titan_operator->getAttribute('ai_model')
        );

        $this->service->handle();

        return response('OK', 200);
    }

    private function verifyWebhook($verifyToken)
    {
        // Meta webhook verification (GET with hub_challenge)
        if (isset($_GET['hub_verify_token']) && $_GET['hub_verify_token'] === $verifyToken) {
            if (isset($_GET['hub_challenge'])) {
                return response($_GET['hub_challenge'], 200);
            }
        }

        return null;
    }
}
