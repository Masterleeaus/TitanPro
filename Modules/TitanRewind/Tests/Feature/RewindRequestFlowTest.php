<?php

namespace Modules\TitanRewind\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\TitanRewind\Actions\ApplyRewindAction;
use Modules\TitanRewind\Actions\ApproveRewindAction;
use Modules\TitanRewind\Actions\RequestRewindAction;
use Modules\TitanRewind\AI\Tools\AnalyseAuditDriftTool;
use Modules\TitanRewind\Models\RewindCase;
use Modules\TitanRewind\Models\RewindEvent;
use Tests\TestCase;

class RewindRequestFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_request_approval_apply_flow_and_ai_drift_analysis(): void
    {
        $case = RewindCase::query()->create([
            'company_id' => 501,
            'case_key' => 'invoice:77',
            'title' => 'Invoice rewind flow',
            'status' => 'open',
            'severity' => 'high',
        ]);

        $request = app(RequestRewindAction::class)->execute($case, [
            'target_table' => 'titan_rewind_cases',
            'target_id' => $case->id,
            'meta_key' => 'status',
            'meta_value' => 'rewound',
            'fix_type' => 'metadata_update',
        ], [
            'type' => 'user',
            'id' => 42,
            'company_id' => 501,
        ]);

        app(ApproveRewindAction::class)->execute($request, [
            'type' => 'user',
            'id' => 42,
            'company_id' => 501,
        ]);

        $applied = app(ApplyRewindAction::class)->execute($request->fresh(), [
            'type' => 'user',
            'id' => 42,
            'company_id' => 501,
        ]);

        $this->assertSame('applied', $applied->status);

        RewindEvent::query()->create([
            'company_id' => 501,
            'case_id' => $case->id,
            'event_type' => 'snapshot_captured',
            'entity_type' => 'invoice',
            'entity_id' => '77',
            'idempotency_key' => 'dup-1',
            'payload_json' => ['example' => true],
            'created_at' => now(),
        ]);

        RewindEvent::query()->create([
            'company_id' => 501,
            'case_id' => $case->id,
            'event_type' => 'snapshot_captured',
            'entity_type' => 'invoice',
            'entity_id' => '77',
            'idempotency_key' => 'dup-2',
            'payload_json' => ['example' => true],
            'created_at' => now(),
        ]);

        $analysis = app(AnalyseAuditDriftTool::class)->execute([
            'company_id' => 501,
            'case_id' => $case->id,
        ]);

        $this->assertIsArray($analysis['anomalies']);
        $this->assertGreaterThan(0.0, $analysis['confidence']);
    }
}
