<?php
namespace App\Extensions\TitanPulse\System\Services;
use App\Extensions\TitanPulse\System\Models\AutomationRun;
use App\Extensions\TitanPulse\System\Models\AutomationRule;
use App\Extensions\TitanPulse\System\Models\Signal;
use Illuminate\Support\Facades\Schema;
class PulseRunRecorder {
    public function start(AutomationRule $rule, ?Signal $signal, string $idempotencyKey, array $context=[]): AutomationRun {
        $data=['rule_id'=>$rule->id,'team_id'=>$rule->team_id,'user_id'=>$rule->user_id,'status'=>'running','context'=>['signal_id'=>$signal?->id,'signal_type'=>$signal?->signal_type,'context'=>$context,'idempotency_key'=>$idempotencyKey],'started_at'=>now(),'created_at'=>now()];
        if (Schema::hasColumn('tz_automation_runs','idempotency_key')) $data['idempotency_key']=$idempotencyKey;
        return AutomationRun::query()->create($data);
    }
    public function finish(AutomationRun $run, array $result, string $status='success'): void { $run->status=$status; $run->result=$result; $run->finished_at=now(); $run->save(); }
}
