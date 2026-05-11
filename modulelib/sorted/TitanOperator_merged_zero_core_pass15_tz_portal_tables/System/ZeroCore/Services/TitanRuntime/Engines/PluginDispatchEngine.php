<?php

namespace App\Extensions\TitanOperator\System\ZeroCore\Services\TitanRuntime\Engines;

use App\Extensions\TitanOperator\System\ZeroCore\Services\TitanRuntime\Registry\PluginRegistry;

class PluginDispatchEngine
{
    public function __construct(
        protected PluginRegistry $plugins,
        protected ToolExecutionEngine $tools,
        protected TamboToolEngine $tambo,
    ) {
    }

    public function resolve(string $prompt): array
    {
        $needle = mb_strtolower($prompt);

        if (str_contains($needle, 'create') || str_contains($needle, 'new job')) {
            return $this->plugins->find('tambo.form') ?? $this->plugins->find('jobs.create');
        }

        if (str_contains($needle, 'table') || str_contains($needle, 'list') || str_contains($needle, 'jobs')) {
            return $this->plugins->find('tambo.table') ?? $this->plugins->find('jobs.lookup');
        }

        if (str_contains($needle, 'memory') || str_contains($needle, 'site')) {
            return $this->plugins->find('memory.site');
        }

        return $this->plugins->find('jobs.lookup');
    }

    public function execute(string $prompt): array
    {
        $plugin = $this->resolve($prompt);
        $key = $plugin['key'] ?? 'jobs.lookup';

        if (str_starts_with($key, 'tambo.')) {
            $result = $this->tambo->execute($key, ['prompt' => $prompt]);
        } else {
            $result = $this->tools->execute($key, ['prompt' => $prompt]);
        }

        return [
            'plugin' => $plugin,
            'result' => $result,
        ];
    }
}
