<?php

namespace Modules\TitanNexus\Events\Voice;

class VoiceWebhookReceived
{
    public function __construct(public array $payload) {}
}
