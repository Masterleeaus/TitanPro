
<?php

namespace App\Extensions\TitanPulse\System\Core\PulseKernel\Services;

class WorkflowRunRecorder
{
    public function start(int $workflowId, array $context): array
    {
        return [
            'workflow_id' => $workflowId,
            'context' => $context,
            'status' => 'running',
            'started_at' => now(),
        ];
    }
}
