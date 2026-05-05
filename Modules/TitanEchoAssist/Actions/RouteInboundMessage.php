<?php

namespace Modules\TitanChatbot\Actions;

use Modules\TitanChatbot\Services\ChannelRouter;

class RouteInboundMessage
{
    public function __construct(private readonly ?ChannelRouter $router = null) {}

    /** @param array<string, mixed> $payload */
    public function handle(array $payload): array
    {
        $channel = (string) ($payload['channel'] ?? 'webchat');
        $message = (string) ($payload['message'] ?? '');

        if ($this->router && method_exists($this->router, 'route')) {
            return ['ok' => true, 'result' => $this->router->route($channel, $payload)];
        }

        return [
            'ok' => $message !== '',
            'channel' => $channel,
            'message' => $message,
            'routed' => $message !== '',
        ];
    }
}
