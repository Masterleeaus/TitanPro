<?php

namespace App\Extensions\TitanOperator\System\ZeroCore\Services\TitanZero\Engines;

use App\Extensions\TitanOperator\System\ZeroCore\Services\TitanZero\Systems\TitanZeroSystem;

class TitanZeroEngine
{
    public function __construct(protected TitanZeroSystem $system) {}

    public function indexPayload(): array
    {
        return $this->system->dashboard();
    }

    public function think(string $intent, array $payload = []): array
    {
        return $this->system->propose($intent, $payload);
    }
}
