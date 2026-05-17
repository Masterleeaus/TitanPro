
<?php

namespace App\Extensions\TitanPulse\System\Core\PulseEngine\Services;

use App\Extensions\TitanPulse\System\Models\AutomationRule;
use Illuminate\Database\Eloquent\Builder;

class ScheduledPulseSourceService
{
    public function dueSweepRules(string $cadence): Builder
    {
        return AutomationRule::query()
            ->where('is_enabled', 1)
            ->where('trigger_type', 'schedule')
            ->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(trigger_config, '$.cadence')) = ?", [$cadence]);
    }
}
