<?php

namespace App\Extensions\TitanPulse\Automation\Rules;

use Illuminate\Support\Facades\DB;

class RuleEngine
{
    public function handleSignal(object $signal): array
    {
        $teamId = (int)($signal->team_id ?? 0);
        if (!$teamId) {
            throw new \RuntimeException('Signal missing team_id');
        }

        $event = $this->signalEvent($signal);
        $payload = $this->enrichPayload($this->signalEvent($signal), $this->signalPayload($signal));

        $subjectType = $this->subjectType($signal);
        $subjectId = $this->subjectId($signal);

        return $this->handleEvent(
            $teamId,
            (int)($signal->company_id ?? $teamId),
            (int)($signal->user_id ?? 0) ?: null,
            $event,
            $payload,
            $subjectType,
            $subjectId,
            (int)($signal->id ?? 0) ?: null
        );
    }


    /**
     * Handle a generic event for a tenant (used by both real tz_signals and scheduled sweeps).
     * $signalId is optional (null for schedule-generated events).
     */
    public function handleEvent(
        int $teamId,
        int $companyId,
        ?int $userId,
        string $event,
        array $payload,
        ?string $subjectType = null,
        $subjectId = null,
        ?int $signalId = null
    ): array {
        if (!$teamId) {
            throw new \RuntimeException('Missing team_id');
        }

        $payload = $this->enrichPayload($event, $payload);

        $rules = DB::table('tz_automation_rules')
            ->where('team_id', $teamId)
            ->where('enabled', 1)
            ->where('trigger_type', 'signal')
            ->where('trigger_event', $event)
            ->orderBy('id', 'asc')
            ->get();

        $actionsExecuted = [];

        // Build a lightweight signal-like object for action executors.
        $sig = (object)[
            'id' => $signalId,
            'team_id' => $teamId,
            'company_id' => $companyId,
            'user_id' => $userId,
            'signal_type' => $event,
            'entity_type' => $subjectType,
            'entity_id' => $subjectId,
        ];

        foreach ($rules as $rule) {
            if (!$this->conditionsPass($rule->conditions_json, $payload)) {
                continue;
            }

            $actions = $this->decodeJson($rule->actions_json) ?? [];
            foreach ($actions as $action) {
                $actionsExecuted[] = $this->executeAction($sig, $payload, $action);
            }
        }

        return [
            'event' => $event,
            'rules_matched' => count($actionsExecuted) > 0,
            'actions' => $actionsExecuted,
        ];
    }


    
    /**
     * Handle a schedule-triggered event (eg schedule.daily.05:30) for a tenant.
     */
    public function handleScheduleEvent(
        int $teamId,
        int $companyId,
        ?int $userId,
        string $scheduleEvent,
        array $payload,
        ?string $subjectType = null,
        $subjectId = null
    ): array {
        $payload = $this->enrichPayload($scheduleEvent, $payload);

        $rules = DB::table('tz_automation_rules')
            ->where('team_id', $teamId)
            ->where('enabled', 1)
            ->where('trigger_type', 'schedule')
            ->where('trigger_event', $scheduleEvent)
            ->orderBy('id', 'asc')
            ->get();

        $actionsExecuted = [];
        $sig = (object)[
            'id' => null,
            'team_id' => $teamId,
            'company_id' => $companyId,
            'user_id' => $userId,
            'signal_type' => $scheduleEvent,
            'entity_type' => $subjectType,
            'entity_id' => $subjectId,
        ];

        foreach ($rules as $rule) {
            if (!$this->conditionsPass($rule->conditions_json, $payload)) {
                continue;
            }
            $actions = $this->decodeJson($rule->actions_json) ?? [];
            foreach ($actions as $action) {
                $actionsExecuted[] = $this->executeAction($sig, $payload, $action);
            }
        }

        return [
            'event' => $scheduleEvent,
            'rules_matched' => count($actionsExecuted) > 0,
            'actions' => $actionsExecuted,
        ];
    }

private function executeAction(object $signal, array $payload, $action): array
    {
        $actionName = is_array($action) ? ($action['action'] ?? null) : null;
        $params = is_array($action) ? ($action['params'] ?? []) : [];
        if (!$actionName) {
            return ['action' => null, 'status' => 'skipped', 'reason' => 'invalid action'];
        }

        $map = [
            'create_suggestion' => \App\Extensions\TitanPulse\Automation\Actions\CreateSuggestionAction::class,
            'queue_pending_action' => \App\Extensions\TitanPulse\Automation\Actions\QueuePendingAction::class,
            'run_analysis' => \App\Extensions\TitanPulse\Automation\Actions\RunAnalysisAction::class,
        ];

        if (!isset($map[$actionName])) {
            return ['action' => $actionName, 'status' => 'skipped', 'reason' => 'unsupported'];
        }

        return $map[$actionName]::run($signal, $payload, $params);
    }

    private function conditionsPass($conditionsJson, array $payload): bool
    {
        $conditions = $this->decodeJson($conditionsJson);
        if (!$conditions || !is_array($conditions)) {
            return true;
        }

        foreach ($conditions as $cond) {
            if (!is_array($cond)) {
                continue;
            }
            $field = $cond['field'] ?? null;
            $op = $cond['op'] ?? '==';
            $value = $cond['value'] ?? null;
            if (!$field) {
                continue;
            }

            $actual = $this->getByPath($payload, $field);
            if (!$this->compare($actual, $op, $value)) {
                return false;
            }
        }
        return true;
    }

    private function compare($actual, string $op, $expected): bool
    {
        switch ($op) {
            case '==': return $actual == $expected;
            case '===': return $actual === $expected;
            case '!=': return $actual != $expected;
            case '>': return is_numeric($actual) && $actual > $expected;
            case '>=': return is_numeric($actual) && $actual >= $expected;
            case '<': return is_numeric($actual) && $actual < $expected;
            case '<=': return is_numeric($actual) && $actual <= $expected;
            case 'in':
                return is_array($expected) ? in_array($actual, $expected, true) : false;
            case 'contains':
                return is_string($actual) && is_string($expected) && str_contains($actual, $expected);
            case 'contains_any':
                if (!is_string($actual) || !is_array($expected)) return false;
                foreach ($expected as $kw) {
                    if (is_string($kw) && $kw !== '' && str_contains(mb_strtolower($actual), mb_strtolower($kw))) {
                        return true;
                    }
                }
                return false;
            case 'exists':
                return $actual !== null;
            default:
                return false;
        }
    }

    /**
     * Enrich payload with derived fields used by rule packs.
     * Keeps everything bounded and deterministic (no AI in the engine itself).
     */
    private function enrichPayload(string $event, array $payload): array
    {
        // Evidence quality heuristic
        $evidenceRequired = $this->getByPath($payload, 'payload.evidence_required');
        if ($evidenceRequired === null) {
            $evidenceRequired = $this->getByPath($payload, 'evidence_required');
        }
        $evidenceCount = $this->getByPath($payload, 'payload.evidence_count');
        if ($evidenceCount === null) {
            $evidenceCount = $this->getByPath($payload, 'evidence_count');
        }

        if ($evidenceRequired === true) {
            $cnt = is_numeric($evidenceCount) ? (int)$evidenceCount : 0;
            $imgQuality = $this->getByPath($payload, 'image_quality');
            $pairsOk = $this->getByPath($payload, 'before_after_pairs_ok');

            $payload['evidence_quality_low'] = ($cnt < 2) || ($imgQuality === 'low') || ($pairsOk === false);
        }

        // Normalized text fields
        $notes = $this->getByPath($payload, 'notes');
        if ($notes === null) {
            $notes = $this->getByPath($payload, 'payload.notes');
        }
        if (is_string($notes) && $notes !== '') {
            $payload['notes'] = $notes;
        }

        // Duration ratio
        $actual = $this->getByPath($payload, 'actual_duration');
        if ($actual === null) $actual = $this->getByPath($payload, 'payload.actual_duration');
        $expected = $this->getByPath($payload, 'expected_duration');
        if ($expected === null) $expected = $this->getByPath($payload, 'payload.expected_duration');
        if (is_numeric($actual) && is_numeric($expected) && (float)$expected > 0.0) {
            $payload['duration_ratio'] = (float)$actual / (float)$expected;
        }

        return $payload;
    }

    private function getByPath(array $payload, string $path)
    {
        // Supports paths like "payload.invoice_required" or "invoice_required"
        $path = str_starts_with($path, 'payload.') ? substr($path, 8) : $path;
        $parts = explode('.', $path);
        $cur = $payload;
        foreach ($parts as $p) {
            if (!is_array($cur) || !array_key_exists($p, $cur)) {
                return null;
            }
            $cur = $cur[$p];
        }
        return $cur;
    }

    private function signalEvent(object $signal): string
    {
        if (property_exists($signal, 'signal_type') && $signal->signal_type) return (string)$signal->signal_type;
        if (property_exists($signal, 'type') && $signal->type) return (string)$signal->type;
        return 'unknown';
    }

    private function signalPayload(object $signal): array
    {
        // Accept multiple schema variants
        if (property_exists($signal, 'payload_json') && $signal->payload_json) {
            $decoded = $this->decodeJson($signal->payload_json);
            return is_array($decoded) ? $decoded : [];
        }
        if (property_exists($signal, 'action_payload') && $signal->action_payload) {
            return is_array($signal->action_payload) ? $signal->action_payload : [];
        }
        return [];
    }

    private function decodeJson($value)
    {
        if ($value === null || $value === '') return null;
        if (is_array($value)) return $value;
        try {
            return json_decode((string)$value, true, 512, JSON_THROW_ON_ERROR);
        } catch (\Throwable $e) {
            return null;
        }
    }
}
