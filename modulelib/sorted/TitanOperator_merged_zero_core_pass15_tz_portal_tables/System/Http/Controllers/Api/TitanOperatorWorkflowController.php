<?php

declare(strict_types=1);

namespace App\Extensions\TitanOperator\System\Http\Controllers\Api;

use App\Extensions\TitanOperator\System\Models\TitanOperator;
use App\Extensions\TitanOperator\System\Models\TitanOperatorConversation;
use App\Extensions\TitanOperator\System\Models\TitanOperatorWorkflowRun;
use App\Extensions\TitanOperator\System\Workflow\ToolRunner;
use App\Extensions\TitanOperator\System\Workflow\WorkflowRegistry;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TitanOperatorWorkflowController
{
    public function __construct(
        protected WorkflowRegistry $registry = new WorkflowRegistry(),
        protected ToolRunner $toolRunner = new ToolRunner(),
    ) {}

    public function list(TitanOperator $titan_operator, string $sessionId): JsonResponse
    {
        return response()->json([
            'ok' => true,
            'session_id' => $sessionId,
            'operator_uuid' => $titan_operator->uuid,
            'workflows' => $this->registry->enabledFor($titan_operator),
            'tools' => $this->registry->toolsEnabledFor($titan_operator),
        ]);
    }

    public function propose(Request $request, TitanOperator $titan_operator, string $sessionId): JsonResponse
    {
        $workflowKey = (string) $request->input('workflow_key');
        $input = $request->input('input', []);
        $conversationId = $request->input('conversation_id');

        $wf = $this->registry->get($workflowKey);
        if (!$wf) {
            return response()->json(['ok' => false, 'error' => 'unknown_workflow'], 422);
        }

        $conversation = $this->findConversation($titan_operator, $sessionId, $conversationId);

        // Validate required fields early so the UI can prompt the user.
        $required = is_array($wf['required_fields'] ?? null) ? $wf['required_fields'] : [];
        $missing = [];
        $inputArr = is_array($input) ? $input : ['value' => $input];
        foreach ($required as $field) {
            if (!is_string($field) || $field === '') {
                continue;
            }
            if (!array_key_exists($field, $inputArr) || $inputArr[$field] === null || $inputArr[$field] === '') {
                $missing[] = $field;
            }
        }
        if (!empty($missing)) {
            return response()->json([
                'ok' => false,
                'error' => 'missing_required_fields',
                'missing' => $missing,
            ], 422);
        }

        $run = TitanOperatorWorkflowRun::create([
            'operator_id' => $titan_operator->getKey(),
            'conversation_id' => $conversation?->getKey(),
            'workflow_key' => $workflowKey,
            'status' => 'proposed',
            'input' => $inputArr,
        ]);

        return response()->json([
            'ok' => true,
            'run' => [
                'id' => $run->getKey(),
                'status' => $run->status,
                'workflow_key' => $workflowKey,
                'requires_confirmation' => (bool)($wf['requires_confirmation'] ?? true),
            ],
            'workflow' => $wf,
        ]);
    }

    public function confirm(Request $request, TitanOperator $titan_operator, string $sessionId): JsonResponse
    {
        $runId = (int) $request->input('run_id');
        $run = TitanOperatorWorkflowRun::query()
            ->where('id', $runId)
            ->where('operator_id', $titan_operator->getKey())
            ->first();

        if (!$run) {
            return response()->json(['ok' => false, 'error' => 'run_not_found'], 404);
        }

        $run->status = 'confirmed';
        $run->confirmed_at = now();
        $run->save();

        return response()->json(['ok' => true, 'run' => $run]);
    }

    public function execute(Request $request, TitanOperator $titan_operator, string $sessionId): JsonResponse
    {
        $runId = (int) $request->input('run_id');
        $run = TitanOperatorWorkflowRun::query()
            ->where('id', $runId)
            ->where('operator_id', $titan_operator->getKey())
            ->first();

        if (!$run) {
            return response()->json(['ok' => false, 'error' => 'run_not_found'], 404);
        }

        $wf = $this->registry->get($run->workflow_key);
        if (!$wf) {
            $run->status = 'failed';
            $run->result = ['error' => 'unknown_workflow'];
            $run->save();
            return response()->json(['ok' => false, 'error' => 'unknown_workflow'], 422);
        }

        if (($wf['requires_confirmation'] ?? true) && $run->confirmed_at === null) {
            return response()->json(['ok' => false, 'error' => 'not_confirmed'], 409);
        }

        $conversation = $run->conversation_id
            ? TitanOperatorConversation::query()->where('id', $run->conversation_id)->where('operator_id', $titan_operator->getKey())->first()
            : null;

        $run->status = 'executing';
        $run->executed_at = now();
        $run->save();

        $steps = is_array($wf['steps'] ?? null) ? $wf['steps'] : [];
        $stepResults = [];
        $ok = true;

        $context = [
            'session_id' => $sessionId,
            'conversation_id' => $conversation?->getKey(),
        ];

        foreach ($steps as $idx => $step) {
            if (!is_array($step)) {
                continue;
            }
            $r = $this->toolRunner->runStep($titan_operator, $conversation, $run, $step, $context);
            $stepResults[] = ['step' => $idx, 'tool' => $step['tool'] ?? null, 'result' => $r];
            if (($r['ok'] ?? false) !== true) {
                $ok = false;
                if (($step['stop_on_fail'] ?? true) === true) {
                    break;
                }
            }
        }

        $run->result = ['ok' => $ok, 'steps' => $stepResults];
        $run->status = $ok ? 'completed' : 'failed';
        $run->completed_at = now();
        $run->save();

        return response()->json(['ok' => $ok, 'run' => $run]);
    }

    private function findConversation(TitanOperator $titan_operator, string $sessionId, mixed $conversationId): ?TitanOperatorConversation
    {
        if (!$conversationId) {
            return null;
        }

        return TitanOperatorConversation::query()
            ->where('operator_id', $titan_operator->getKey())
            ->where('session_id', $sessionId)
            ->where('id', (int) $conversationId)
            ->first();
    }
}
