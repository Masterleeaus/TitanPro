<?php
namespace App\Extensions\TitanPulse\System\Services;
use App\Extensions\TitanPulse\System\Models\AutomationRule;
use App\Extensions\TitanPulse\System\Models\Signal;
use App\Extensions\TitanPulse\System\Services\Conditions\ConditionRegistry;
use App\Extensions\TitanPulse\System\Services\Conditions\Payload\PayloadAccessor;
use App\Extensions\TitanPulse\System\Services\Idempotency\IdempotencyKey;
use App\Extensions\TitanPulse\System\Services\Idempotency\IdempotencyStore;
use App\Extensions\TitanPulse\System\Services\Locks\PulseLock;
use App\Extensions\TitanPulse\System\Services\RateLimit\PulseRateLimiter;
class PulseRuleEngine {
    public function __construct(private ConditionRegistry $conditions, private PayloadAccessor $payloadAccessor, private PulseActionRegistry $actions, private PulseRunRecorder $recorder, private IdempotencyStore $idempotencyStore, private PulseLock $lock, private PulseRateLimiter $rateLimiter) {}
    public function evaluateRule(AutomationRule $rule, ?Signal $signal, array $context=[]): array {
        $payload=array_merge($signal?->payload_json ?? [], $context); $teamId=(int)($rule->team_id ?: ($signal?->team_id ?? 0)); if ($teamId<=0) return ['status'=>'skipped','reason'=>'missing_team'];
        $idempotencyKey=$signal ? IdempotencyKey::forSignal($teamId,(int)$rule->id,(int)$signal->id) : IdempotencyKey::forSweep($teamId,(int)$rule->id,(string)($context['subject'] ?? 'team'),(string)($context['bucket'] ?? now()->format('Y-m-d-H')));
        if ($this->idempotencyStore->has($idempotencyKey,$teamId)) return ['status'=>'skipped','reason'=>'idempotent'];
        if (!$this->lock->acquire($idempotencyKey,120)) return ['status'=>'skipped','reason'=>'locked'];
        if ($this->rateLimiter->tooManyAttempts('titan_pulse:'.$idempotencyKey,3,120)) { $this->lock->release($idempotencyKey); return ['status'=>'skipped','reason'=>'rate_limited']; }
        try {
            $conditions=$rule->action_config['conditions'] ?? $rule->trigger_config['conditions'] ?? [];
            if (!$this->passesConditions($payload,$conditions)) return ['status'=>'skipped','reason'=>'conditions_failed'];
            $run=$this->recorder->start($rule,$signal,$idempotencyKey,$context); $handler=$this->actions->get((string)($rule->action_type ?: 'create_suggestion'));
            if (!$handler) { $this->recorder->finish($run,['reason'=>'missing_action_handler'],'failed'); return ['status'=>'failed','reason'=>'missing_action_handler']; }
            $artifact=$handler->execute($rule,$signal,$rule->action_config ?? [],$context); $result=['status'=>'success','artifact_type'=>class_basename($artifact),'artifact_id'=>$artifact->id ?? null];
            $this->recorder->finish($run,$result,'success'); return $result;
        } finally { $this->lock->release($idempotencyKey); }
    }
    private function passesConditions(array $payload, array $conditions): bool { foreach($conditions as $condition){ $path=(string)($condition['path'] ?? ''); $operator=(string)($condition['operator'] ?? 'eq'); $expected=$condition['value'] ?? null; $actual=$this->payloadAccessor->get($payload,$path); $handler=$this->conditions->get($operator); if (!method_exists($handler,'passes') || !$handler->passes($actual,$expected)) return false; } return true; }
}
