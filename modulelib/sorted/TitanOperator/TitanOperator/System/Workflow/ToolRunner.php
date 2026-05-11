<?php

declare(strict_types=1);

namespace App\Extensions\TitanOperator\System\Workflow;

use App\Extensions\TitanOperator\System\Models\TitanOperator;
use App\Extensions\TitanOperator\System\Models\TitanOperatorConversation;
use App\Extensions\TitanOperator\System\Models\TitanOperatorWorkflowRun;
use App\Extensions\TitanOperator\System\Workflow\Tools\AuditLogTool;
use App\Extensions\TitanOperator\System\Workflow\Tools\CalendarCreateTool;
use App\Extensions\TitanOperator\System\Workflow\Tools\FileRequestTool;
use App\Extensions\TitanOperator\System\Workflow\Tools\NotifyChannelTool;
use App\Extensions\TitanOperator\System\Workflow\Tools\NotifyEmailTool;
use App\Extensions\TitanOperator\System\Workflow\Tools\NotifySmsTool;
use App\Extensions\TitanOperator\System\Workflow\Tools\RecordCreateTool;
use App\Extensions\TitanOperator\System\Workflow\Tools\RecordFindTool;
use App\Extensions\TitanOperator\System\Workflow\Tools\RecordUpdateTool;
use App\Extensions\TitanOperator\System\Workflow\Tools\WebhookCallTool;

class ToolRunner
{
    public function __construct(
        protected WebhookCallTool $webhookCallTool = new WebhookCallTool(),
        protected AuditLogTool $auditLogTool = new AuditLogTool(),
        protected RecordCreateTool $recordCreateTool = new RecordCreateTool(),
        protected RecordUpdateTool $recordUpdateTool = new RecordUpdateTool(),
        protected RecordFindTool $recordFindTool = new RecordFindTool(),
        protected NotifyEmailTool $notifyEmailTool = new NotifyEmailTool(),
        protected NotifySmsTool $notifySmsTool = new NotifySmsTool(),
        protected NotifyChannelTool $notifyChannelTool = new NotifyChannelTool(),
        protected CalendarCreateTool $calendarCreateTool = new CalendarCreateTool(),
        protected FileRequestTool $fileRequestTool = new FileRequestTool(),
        protected WorkflowRegistry $registry = new WorkflowRegistry(),
    ) {}

    /**
     * @param array<string,mixed> $step
     * @param array<string,mixed> $context
     * @return array<string,mixed>
     */
    public function runStep(TitanOperator $titan_operator, ?TitanOperatorConversation $conversation, TitanOperatorWorkflowRun $run, array $step, array $context = []): array
    {
        $tool = (string)($step['tool'] ?? '');

        // Tool enable/disable gate (per titan_operator)
        if ($tool !== '' && $tool !== 'webhook.call' && $tool !== 'audit.log') {
            if (!$this->registry->toolIsEnabledFor($titan_operator, $tool)) {
                return [
                    'ok' => false,
                    'error' => 'tool_disabled',
                    'tool' => $tool,
                ];
            }
        }

        if ($tool === 'webhook.call') {
            return $this->webhookCallTool->handle($titan_operator, $conversation, $run, $step, $context);
        }
        if ($tool === 'audit.log') {
            return $this->auditLogTool->handle($titan_operator, $conversation, $run, $step, $context);
        }

        // First-class tools (provide a stable payload contract + validation)
        return match ($tool) {
            'record.create' => $this->recordCreateTool->handle($titan_operator, $conversation, $run, $step, $context),
            'record.update' => $this->recordUpdateTool->handle($titan_operator, $conversation, $run, $step, $context),
            'record.find' => $this->recordFindTool->handle($titan_operator, $conversation, $run, $step, $context),
            'notify.email' => $this->notifyEmailTool->handle($titan_operator, $conversation, $run, $step, $context),
            'notify.sms' => $this->notifySmsTool->handle($titan_operator, $conversation, $run, $step, $context),
            'notify.channel' => $this->notifyChannelTool->handle($titan_operator, $conversation, $run, $step, $context),
            'calendar.create' => $this->calendarCreateTool->handle($titan_operator, $conversation, $run, $step, $context),
            'file.request' => $this->fileRequestTool->handle($titan_operator, $conversation, $run, $step, $context),
            default => null,
        } ?? $this->fallback($titan_operator, $conversation, $run, $step, $context);
    }

    /** @return array<string,mixed> */
    protected function fallback(TitanOperator $titan_operator, ?TitanOperatorConversation $conversation, TitanOperatorWorkflowRun $run, array $step, array $context = []): array
    {
        $tool = (string)($step['tool'] ?? '');

        // Webhook-first: for any allowlisted tool, call the external tool gateway
        // using the tool name as the action. This keeps the internal implementation minimal
        // while preserving a stable workflow/tool contract.
        $allow = $this->registry->toolsAllowlist();
        if (in_array($tool, $allow, true)) {
            $step['action'] = $step['action'] ?? $tool;
            // Pass the tool name through for external dispatch.
            $step['tool'] = 'webhook.call';
            return $this->webhookCallTool->handle($titan_operator, $conversation, $run, $step, $context);
        }

        return [
            'ok' => false,
            'error' => 'tool_not_implemented',
            'tool' => $tool,
        ];
    }
}
