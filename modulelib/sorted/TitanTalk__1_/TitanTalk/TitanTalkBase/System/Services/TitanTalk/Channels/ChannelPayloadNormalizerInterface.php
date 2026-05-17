<?php

declare(strict_types=1);

namespace App\Extensions\MarketingBot\System\Services\TitanTalk\Channels;

interface ChannelPayloadNormalizerInterface
{
    /**
     * @param array<string,mixed> $payload
     * @return array<string,mixed>
     */
    public function normalize(array $payload): array;
}
