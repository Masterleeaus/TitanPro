<?php

declare(strict_types=1);

namespace App\Extensions\MarketingBot\System\Channels\Contracts;

interface ChannelAdapter
{
    /** @param array<string,mixed> $payload
     * @return array<string,mixed>
     */
    public function normalizeInbound(array $payload): array;

    /** @param array<string,mixed> $payload
     * @return array<string,mixed>
     */
    public function sendMessage(array $payload): array;

    /** @param array<string,mixed> $payload
     * @return array<string,mixed>
     */
    public function extractParticipant(array $payload): array;

    public function key(): string;
}
