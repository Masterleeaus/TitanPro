<?php

use App\Extensions\TitanPulse\Services\SignalBus\SignalEmitter;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

it('emits a signal and creates a pending action when a rule matches', function () {
    DB::table('tz_automation_rules')->insert([
        'team_id' => 101,
        'company_id' => 101,
        'user_id' => 1,
        'trigger_type' => 'signal',
        'trigger_event' => 'work.job.status_changed',
        'conditions_json' => json_encode([
            [
                'field' => 'new_status',
                'op' => '==',
                'value' => 'completed',
            ],
        ], JSON_UNESCAPED_UNICODE),
        'actions_json' => json_encode([
            [
                'action' => 'queue_pending_action',
                'params' => [
                    'action_type' => 'job_follow_up',
                    'title' => 'Follow up completed job',
                ],
            ],
        ], JSON_UNESCAPED_UNICODE),
        'enabled' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $signalId = SignalEmitter::emit(
        'work.job.status_changed',
        'field_job',
        999,
        ['new_status' => 'completed'],
        ['team_id' => 101, 'company_id' => 101, 'user_id' => 1, 'idempotency_key' => 'job-999-completed'],
    );

    $this->assertDatabaseHas('tz_signals', [
        'id' => $signalId,
        'team_id' => 101,
        'type' => 'work.job.status_changed',
    ]);

    Artisan::call('titan:pulse-run', [
        '--signals-only' => true,
        '--limit' => 50,
        '--team_id' => 101,
    ]);

    $this->assertDatabaseHas('tz_pending_actions', [
        'team_id' => 101,
        'action_type' => 'job_follow_up',
        'subject_type' => 'field_job',
        'subject_id' => 999,
        'status' => 'pending',
    ]);

    $this->assertDatabaseHas('tz_automation_runs', [
        'team_id' => 101,
        'signal_id' => $signalId,
        'status' => 'ok',
    ]);
});
