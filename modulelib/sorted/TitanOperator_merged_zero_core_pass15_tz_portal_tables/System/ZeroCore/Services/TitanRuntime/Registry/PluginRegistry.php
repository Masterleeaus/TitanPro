<?php

namespace App\Extensions\TitanOperator\System\ZeroCore\Services\TitanRuntime\Registry;

class PluginRegistry
{
    public function all(): array
    {
        return [
            ['key' => 'jobs.lookup', 'label' => 'Jobs Lookup', 'group' => 'work', 'surface' => 'canvas', 'assistant' => 'work-ai'],
            ['key' => 'jobs.create', 'label' => 'Create Job', 'group' => 'work', 'surface' => 'canvas', 'assistant' => 'work-ai'],
            ['key' => 'memory.site', 'label' => 'Site Memory', 'group' => 'memory', 'surface' => 'canvas', 'assistant' => 'memory-ai'],
            ['key' => 'memory.job', 'label' => 'Job Memory', 'group' => 'memory', 'surface' => 'canvas', 'assistant' => 'memory-ai'],
            ['key' => 'signals.inspect', 'label' => 'Signal Inspect', 'group' => 'signals', 'surface' => 'timeline', 'assistant' => 'signal-ai'],
            ['key' => 'tambo.form', 'label' => 'Tambo Form Renderer', 'group' => 'tambo', 'surface' => 'canvas', 'assistant' => 'work-ai'],
            ['key' => 'tambo.table', 'label' => 'Tambo Table Renderer', 'group' => 'tambo', 'surface' => 'canvas', 'assistant' => 'work-ai'],
            ['key' => 'tambo.action', 'label' => 'Tambo Action Handler', 'group' => 'tambo', 'surface' => 'canvas', 'assistant' => 'work-ai'],
        ];
    }

    public function find(string $key): ?array
    {
        foreach ($this->all() as $plugin) {
            if (($plugin['key'] ?? null) === $key) {
                return $plugin;
            }
        }

        return null;
    }
}
