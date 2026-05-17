<?php

namespace App\Extensions\TitanOperator\System\ZeroCore\Services\TitanRuntime\Registry;

class AssistantRegistry
{
    public function all(): array
    {
        return [
            ['key' => 'work-ai', 'label' => 'Work AI', 'capabilities' => ['jobs.create','jobs.lookup','tambo.form']],
            ['key' => 'memory-ai', 'label' => 'Memory AI', 'capabilities' => ['memory.site','memory.job']],
            ['key' => 'signal-ai', 'label' => 'Signal AI', 'capabilities' => ['signals.inspect']],
        ];
    }

    public function match(array $plugin): array
    {
        foreach ($this->all() as $assistant) {
            if (in_array($plugin['key'] ?? '', $assistant['capabilities'])) {
                return $assistant;
            }
        }
        return $this->all()[0];
    }
}
