<?php

namespace Modules\TitanRewind\Tests\Unit\Actions;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\TitanRewind\Actions\ApplyRewindAction;
use Modules\TitanRewind\Models\RewindCase;
use Modules\TitanRewind\Models\RewindEvent;
use Modules\TitanRewind\Models\RewindFix;
use Tests\TestCase;

class ApplyRewindActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_apply_action_requires_prior_rewind_approved_event(): void
    {
        $case = RewindCase::query()->create([
            'company_id' => 101,
            'case_key' => 'order:1003',
            'title' => 'Apply case',
            'status' => 'open',
            'severity' => 'high',
        ]);

        $fix = RewindFix::query()->create([
            'company_id' => 101,
            'case_id' => $case->id,
            'fix_type' => 'metadata_update',
            'status' => 'confirmed',
            'requires_confirmation' => true,
            'proposal_json' => [
                'target_table' => 'titan_rewind_cases',
                'target_id' => $case->id,
                'meta_key' => 'note',
                'meta_value' => 'must be gated',
            ],
        ]);

        $this->expectException(\RuntimeException::class);

        app(ApplyRewindAction::class)->execute($fix, [
            'type' => 'user',
            'id' => 3,
            'company_id' => 101,
        ]);
    }

    public function test_apply_action_executes_after_approval_event(): void
    {
        $case = RewindCase::query()->create([
            'company_id' => 101,
            'case_key' => 'order:1004',
            'title' => 'Apply case approved',
            'status' => 'open',
            'severity' => 'high',
            'meta_json' => [],
        ]);

        $fix = RewindFix::query()->create([
            'company_id' => 101,
            'case_id' => $case->id,
            'fix_type' => 'metadata_update',
            'status' => 'confirmed',
            'requires_confirmation' => true,
            'proposal_json' => [
                'target_table' => 'titan_rewind_cases',
                'target_id' => $case->id,
                'meta_key' => 'note',
                'meta_value' => 'approved',
            ],
        ]);

        RewindEvent::query()->create([
            'company_id' => 101,
            'case_id' => $case->id,
            'event_type' => 'rewind_approved',
            'actor_type' => 'user',
            'actor_id' => 3,
            'idempotency_key' => 'approved-'.$fix->id,
            'payload_json' => ['fix_id' => $fix->id],
            'created_at' => now(),
        ]);

        $applied = app(ApplyRewindAction::class)->execute($fix, [
            'type' => 'user',
            'id' => 3,
            'company_id' => 101,
        ]);

        $this->assertSame('applied', $applied->status);
    }
}
