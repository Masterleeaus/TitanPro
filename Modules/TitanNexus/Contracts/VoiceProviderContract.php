<?php

namespace Modules\TitanNexus\Contracts;

interface VoiceProviderContract
{
    public function startCall(array $lead, array $script, array $context = []): array;
    public function handleWebhook(array $payload): array;
}
