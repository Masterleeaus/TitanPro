<?php
namespace App\Extensions\TitanPulse\System\Services\Actions;
use App\Extensions\TitanPulse\System\Models\AiSuggestion;
use App\Extensions\TitanPulse\System\Models\AutomationRule;
use App\Extensions\TitanPulse\System\Models\Signal;
class CreateSuggestionAction {
    public function execute(AutomationRule $rule, ?Signal $signal, array $actionConfig, array $context=[]): AiSuggestion {
        return AiSuggestion::query()->create([
            'company_id'=>$rule->team_id,
            'team_id'=>$rule->team_id,
            'user_id'=>$rule->user_id,
            'entity_type'=>$signal?->entity_type ?? ($context['entity_type'] ?? null),
            'entity_id'=>$signal?->entity_id ?? ($context['entity_id'] ?? null),
            'suggestion_type'=>$actionConfig['suggestion_type'] ?? $rule->action_type,
            'title'=>$actionConfig['title'] ?? $rule->name,
            'body'=>$actionConfig['body'] ?? ($context['body'] ?? null),
            'payload_json'=>json_encode(['rule_id'=>$rule->id,'signal_id'=>$signal?->id,'context'=>$context,'action'=>$actionConfig]),
            'status'=>'pending','score'=>$actionConfig['score'] ?? null,'created_at'=>now(),'updated_at'=>now(),
        ]);
    }
}
