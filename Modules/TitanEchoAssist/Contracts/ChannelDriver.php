<?php

namespace Modules\TitanEchoAssist\Contracts;

interface ChannelDriver
{
    public function handle(array $payload): string;
}
