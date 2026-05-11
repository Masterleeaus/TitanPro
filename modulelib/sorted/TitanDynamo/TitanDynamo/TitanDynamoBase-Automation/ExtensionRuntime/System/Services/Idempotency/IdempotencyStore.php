<?php
namespace App\Extensions\TitanPulse\System\Services\Idempotency;
use App\Extensions\TitanPulse\System\Models\AutomationRun;
use Illuminate\Support\Facades\Schema;
class IdempotencyStore {
    public function has(string $key, ?int $teamId=null): bool {
        if (!Schema::hasColumn('tz_automation_runs','idempotency_key')) return false;
        return AutomationRun::query()->when($teamId, fn($q)=>$q->where('team_id',$teamId))->where('idempotency_key',$key)->exists();
    }
}
