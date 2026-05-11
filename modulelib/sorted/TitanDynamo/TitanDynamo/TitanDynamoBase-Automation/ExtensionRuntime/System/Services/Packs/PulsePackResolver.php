<?php
namespace App\Extensions\TitanPulse\System\Services\Packs;
use App\Extensions\TitanPulse\System\Models\AutomationRule;
use App\Extensions\TitanPulse\System\Models\AutomationRuleSet;
use Illuminate\Support\Collection;
class PulsePackResolver {
    public function __construct(private BuiltInPackCatalog $catalog) {}

    public function enabledPackNames(?int $teamId=null): array {
        $fallback = array_keys($this->catalog->all());
        if (!class_exists(AutomationRuleSet::class)) {
            return $fallback;
        }
        $query = AutomationRuleSet::query()->where('enabled',1)
            ->where(function($q) use ($teamId){
                if ($teamId) {
                    $q->where('team_id',$teamId)->orWhereNull('team_id');
                } else {
                    $q->whereNull('team_id');
                }
            });
        $rows = $query->orderByRaw('team_id is null')->get(['name']);
        $names = $rows->pluck('name')->filter()->unique()->values()->all();
        return $names ?: $fallback;
    }

    public function isRuleEnabledForTeam(AutomationRule $rule, ?int $teamId=null): bool {
        $pack = $this->rulePackName($rule);
        return in_array($pack, $this->enabledPackNames($teamId ?: (int)$rule->team_id ?: null), true);
    }

    public function rulePackName(AutomationRule $rule): string {
        $action = is_array($rule->action_config) ? $rule->action_config : [];
        $trigger = is_array($rule->trigger_config) ? $rule->trigger_config : [];
        return (string)($action['pack'] ?? $trigger['pack'] ?? 'CleaningOpsPack');
    }

    public function attachPackMeta(Collection $rules): Collection {
        return $rules->map(function($rule){
            $rule->resolved_pack = $this->rulePackName($rule);
            return $rule;
        });
    }
}
