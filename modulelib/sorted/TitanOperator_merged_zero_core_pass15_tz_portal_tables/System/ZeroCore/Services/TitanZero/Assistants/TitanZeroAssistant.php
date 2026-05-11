<?php

namespace App\Extensions\TitanOperator\System\ZeroCore\Services\TitanZero\Assistants;

use App\Extensions\TitanOperator\System\ZeroCore\Services\TitanZero\Engines\TitanZeroEngine;

class TitanZeroAssistant
{
    public function __construct(protected TitanZeroEngine $engine) {}

    public function respond(string $prompt, array $context = []): array
    {
        return $this->engine->think($prompt, $context + ['surface' => 'assistant']);
    }
}
