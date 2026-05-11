
<?php

namespace App\Extensions\TitanPulse\System\Core\PulseKernel\Services;

use RuntimeException;

class WorkflowEngine
{
    public function run(string $triggerKey, array $payload = [], array $meta = []): array
    {
        return [
            'trigger' => $triggerKey,
            'payload' => $payload,
            'meta' => $meta,
            'status' => 'imported_kernel_stub',
        ];
    }
}
