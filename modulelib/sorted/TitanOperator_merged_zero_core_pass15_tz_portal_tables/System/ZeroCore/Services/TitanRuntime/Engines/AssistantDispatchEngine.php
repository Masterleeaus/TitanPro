<?php

namespace App\Extensions\TitanOperator\System\ZeroCore\Services\TitanRuntime\Engines;

use App\Extensions\TitanOperator\System\ZeroCore\Services\TitanRuntime\Registry\AssistantRegistry;

class AssistantDispatchEngine
{
    public function __construct(
        protected AssistantRegistry $assistants
    ) {}

    public function assign(array $plugin): array
    {
        return $this->assistants->match($plugin);
    }
}
