<?php

namespace App\Extensions\TitanOperator\System\Channels\Telegram\Services\Telegram;

use App\Extensions\TitanOperator\System\Models\TitanOperatorChannel;
use Exception;
use Illuminate\Support\Facades\Http;

class TelegramService
{
    public TitanOperatorChannel $channel;

    public function sendText($message, $receiver): void
    {
        $token = data_get($this->channel->credentials, 'telegram_token');

        $url = "https://api.telegram.org/bot{$token}/sendMessage";

        $data = [
            'chat_id'    => $receiver,
            'text'       => $message,
        ];

        $url .= '?' . http_build_query($data);

        $http = Http::get($url);

        if ($http->failed()) {
            throw new Exception('Failed to send message: ' . $http->body());
        }

        if (! $http->json('ok')) {
            throw new Exception('Failed to send message: ' . $http->json('description'));
        }
    }

    public function setWebhook(): self
    {
        $wehHookUrl = route('api.v2.titan_operator.channel.telegram.post.handle', [
            'operatorId' => $this->channel->operator_id,
            'channelId' => $this->channel->id,
        ]);

        $token = data_get($this->channel->credentials, 'telegram_token');

        $url = "https://api.telegram.org/bot{$token}/setWebhook?url={$wehHookUrl}";

        $http = Http::get($url);

        if ($http->failed()) {
            throw new Exception('Failed to set webhook: ' . $http->body());
        }

        if ($http->json('ok') !== true) {
            throw new Exception('Failed to set webhook: ' . $http->json('description'));
        }

        return $this;
    }

    public function setChannel(TitanOperatorChannel $channel): TelegramService
    {
        $this->channel = $channel;

        return $this;
    }
}
