<?php

namespace App\Extensions\TitanOperator\System\ZeroCore\Services\TitanRuntime\Engines;

class TamboToolEngine
{
    public function execute(string $tool, array $payload = []): array
    {
        return match ($tool) {
            'tambo.form' => [
                'kind' => 'form',
                'title' => 'Tambo Form',
                'body' => 'Prepared a dynamic form surface for: ' . ($payload['prompt'] ?? 'request'),
            ],
            'tambo.table' => [
                'kind' => 'table',
                'title' => 'Tambo Table',
                'body' => 'Prepared a dynamic table surface for: ' . ($payload['prompt'] ?? 'request'),
            ],
            'tambo.action' => [
                'kind' => 'action',
                'title' => 'Tambo Action',
                'body' => 'Prepared an action workflow for: ' . ($payload['prompt'] ?? 'request'),
            ],
            default => [
                'kind' => 'text',
                'title' => 'Tambo Output',
                'body' => 'No specialised tambo output available.',
            ],
        };
    }
}
