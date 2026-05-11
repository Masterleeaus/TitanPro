<?php
namespace App\Extensions\TitanPulse\System\Services\Actions;
use App\Extensions\TitanPulse\System\Models\AiAgentRun;
use App\Extensions\TitanPulse\System\Models\AutomationRule;
use App\Extensions\TitanPulse\System\Models\Signal;
class RunAnalysisAction {
    public function execute(AutomationRule $rule, ?Signal $signal, array $actionConfig, array $context=[]): AiAgentRun {
        return AiAgentRun::query()->create([
            'company_id'=>$rule->team_id,'team_id'=>$rule->team_id,'user_id'=>$rule->user_id,
            'agent_name'=>'titan_pulse','mode'=>$signal ? 'reactive' : 'scheduled','signal_id'=>$signal?->id,
            'entity_type'=>$signal?->entity_type ?? ($context['entity_type'] ?? null),'entity_id'=>$signal?->entity_id ?? ($context['entity_id'] ?? null),
            'status'=>'success','result_json'=>json_encode(['analysis_type'=>$actionConfig['analysis_type'] ?? 'pulse_analysis','summary'=>$actionConfig['summary'] ?? $rule->name,'context'=>$context]),
            'started_at'=>now(),'ended_at'=>now(),'created_at'=>now(),'updated_at'=>now(),
        ]);
    }
}
