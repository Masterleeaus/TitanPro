<?php
namespace App\Extensions\TitanPulse\System\Services;
use App\Extensions\TitanPulse\System\Models\AutomationRule;
use App\Extensions\TitanPulse\System\Models\Signal;
use App\Extensions\TitanPulse\System\Services\Packs\PulsePackResolver;
class PulseEngine {
    public function __construct(private PulseRuleEngine $ruleEngine, private PulsePackResolver $packs) {}
    public function run(array $options=[]): array {
        $limit=(int)($options['limit'] ?? config('titan-pulse.default_limit',200)); $teamId=$options['team_id'] ?? null; $signalsOnly=(bool)($options['signals_only'] ?? false); $sweepsOnly=(bool)($options['sweeps_only'] ?? false); $onlyPack=(string)($options['pack'] ?? '');
        $stats=['signals_processed'=>0,'sweeps_processed'=>0,'runs_created'=>0,'suggestions_created'=>0,'pending_actions_created'=>0,'analyses_created'=>0];
        if (!$sweepsOnly) { $signals=Signal::query()->when($teamId,fn($q)=>$q->where('team_id',$teamId))->orderBy('id','desc')->limit($limit)->get(); foreach($signals as $signal){ foreach($this->matchingSignalRules($signal, $teamId ?: (int)$signal->team_id, $onlyPack) as $rule){ $result=$this->ruleEngine->evaluateRule($rule,$signal); $stats['signals_processed']++; if (($result['status'] ?? '')==='success'){ $stats['runs_created']++; $this->bump($stats,(string)($result['artifact_type'] ?? '')); } } } }
        if (!$signalsOnly) { $rules=AutomationRule::query()->when($teamId,fn($q)=>$q->where('team_id',$teamId))->where('is_enabled',1)->where('trigger_type','schedule')->limit($limit)->get(); $rules=$this->packs->attachPackMeta($rules)->filter(fn($rule)=>$this->packs->isRuleEnabledForTeam($rule,$teamId))->when($onlyPack !== '', fn($collection)=>$collection->filter(fn($rule)=>($rule->resolved_pack ?? null) === $onlyPack)); foreach($rules as $rule){ if(!$this->isDue($rule->trigger_config ?? [])) continue; $context=['subject'=>'team:'.($rule->team_id ?: '0'),'bucket'=>now()->format('Y-m-d-H'),'body'=>$rule->action_config['body'] ?? null]; $result=$this->ruleEngine->evaluateRule($rule,null,$context); $stats['sweeps_processed']++; if (($result['status'] ?? '')==='success'){ $stats['runs_created']++; $this->bump($stats,(string)($result['artifact_type'] ?? '')); } } }
        return $stats;
    }
    private function matchingSignalRules(Signal $signal, ?int $teamId = null, string $onlyPack = '') { $rules = AutomationRule::query()->where('is_enabled',1)->where('trigger_type','signal')->where(function($q) use ($signal){ $q->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(trigger_config, '$.event')) = ?", [$signal->signal_type])->orWhereRaw("JSON_UNQUOTE(JSON_EXTRACT(trigger_config, '$.signal_type')) = ?", [$signal->signal_type]); })->get(); return $this->packs->attachPackMeta($rules)->filter(fn($rule)=>$this->packs->isRuleEnabledForTeam($rule,$teamId ?: (int)$signal->team_id))->when($onlyPack !== '', fn($collection)=>$collection->filter(fn($rule)=>($rule->resolved_pack ?? null) === $onlyPack)); }
    private function isDue(array $cfg): bool { $cadence=(string)($cfg['cadence'] ?? $cfg['schedule'] ?? 'hourly'); return match($cadence){ 'every_30_min' => now()->minute < 5 || (now()->minute >=30 && now()->minute < 35), 'daily' => now()->minute < 10, 'weekly' => now()->isMonday() && now()->hour < 3, default => true, }; }
    private function bump(array &$stats, string $artifactType): void { if (str_contains($artifactType,'Suggestion')) $stats['suggestions_created']++; elseif (str_contains($artifactType,'PendingAction')) $stats['pending_actions_created']++; else $stats['analyses_created']++; }
}
