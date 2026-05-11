<?php
namespace App\Extensions\TitanPulse\System\Services\Actions;
use App\Extensions\TitanPulse\System\Models\AiPendingAction;
use App\Extensions\TitanPulse\System\Models\AutomationRule;
use App\Extensions\TitanPulse\System\Models\Signal;
class QueuePendingActionAction {
    public function execute(AutomationRule $rule, ?Signal $signal, array $actionConfig, array $context=[]): AiPendingAction {
        return AiPendingAction::query()->create([
            'company_id'=>$rule->team_id,'team_id'=>$rule->team_id,'user_id'=>$rule->user_id,
            'action_type'=>$actionConfig['pending_action_type'] ?? $rule->action_type,
            'entity_type'=>$signal?->entity_type ?? ($context['entity_type'] ?? null),'entity_id'=>$signal?->entity_id ?? ($context['entity_id'] ?? null),
            'payload_json'=>json_encode(['rule_id'=>$rule->id,'signal_id'=>$signal?->id,'context'=>$context,'action'=>$actionConfig]),
            'status'=>'pending','created_at'=>now(),'updated_at'=>now(),
        ]);
    }
}
