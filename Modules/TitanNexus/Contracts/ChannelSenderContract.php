<?php

namespace Modules\TitanNexus\Contracts;

interface ChannelSenderContract
{
    public function send(array $recipient, string $message, array $context = []): array;
}
