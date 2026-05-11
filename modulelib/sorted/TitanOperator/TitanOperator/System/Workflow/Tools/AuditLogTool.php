<?php

declare(strict_types=1);

namespace App\Extensions\TitanOperator\System\Workflow\Tools;

use App\Extensions\TitanOperator\System\Models\TitanOperator;
use App\Extensions\TitanOperator\System\Models\TitanOperatorConversation;
use App\Extensions\TitanOperator\System\Models\TitanOperatorHistory;
use App\Extensions\TitanOperator\System\Models\TitanOperatorWorkflowRun;

class AuditLogTool
{
    /**
     * @param array<string,mixed> $step
     * @param array<string,mixed> $context
     * @return array<string,mixed>
     */
    public function handle(TitanOperator $titan_operator, ?TitanOperatorConversation $conversation, TitanOperatorWorkflowRun $run, array $step, array $context = []): array
    {
        if (!$conversation) {
            return ['ok' => true, 'skipped' => true];
        }

        $summary = [
            'workflow_key' => $run->workflow_key,
            'run_id' => $run->getKey(),
            'status' => $run->status,
            'step' => $step,
        ];

        TitanOperatorHistory::create([
            'operator_id' => $titan_operator->getKey(),
            'conversation_id' => $conversation->getKey(),
            'model' => 'workflow',
            'role' => 'system',
            'message' => json_encode($summary),
            'type' => $conversation->operator_channel ?? 'frame',
            'message_type' => 'tool',
            'content_type' => 'json',
            'created_at' => now(),
        ]);

        return ['ok' => true];
    }
}
