<?php

namespace Modules\TitanRewind\Tests\Unit\Actions;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\TitanRewind\Actions\ApproveRewindAction;
use Modules\TitanRewind\Models\RewindCase;
use Modules\TitanRewind\Models\RewindFix;
use Tests\TestCase;

class ApproveRewindActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_approve_action_marks_request_confirmed(): void
    {
        $case = RewindCase::query()->create([
            'company_id' => 101,
            'case_key' => 'order:1002',
            'title' => 'Approval case',
            'status' => 'open',
            'severity' => 'high',
        ]);

        $fix = RewindFix::query()->create([
            'company_id' => 101,
            'case_id' => $case->id,
            'fix_type' => 'metadata_update',
            'status' => 'proposed',
            'requires_confirmation' => true,
            'proposal_json' => [
                'target_table' => 'titan_rewind_cases',
                'target_id' => $case->id,
                'meta_key' => 'note',
                'meta_value' => 'approve me',
            ],
        ]);

        $approved = app(ApproveRewindAction::class)->execute($fix, [
            'type' => 'user',
            'id' => 2,
            'company_id' => 101,
        ]);

        $this->assertSame('confirmed', $approved->status);
        $this->assertNotNull($approved->confirmed_at);
    }
}
