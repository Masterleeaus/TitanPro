<?php

namespace Modules\TitanRewind\Tests\Unit\Actions;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\TitanRewind\Actions\RequestRewindAction;
use Modules\TitanRewind\Models\RewindCase;
use Tests\TestCase;

class RequestRewindActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_request_action_creates_rewind_fix_request(): void
    {
        $case = RewindCase::query()->create([
            'company_id' => 101,
            'case_key' => 'order:1001',
            'title' => 'Test case',
            'status' => 'open',
            'severity' => 'high',
        ]);

        $fix = app(RequestRewindAction::class)->execute($case, [
            'target_table' => 'titan_rewind_cases',
            'target_id' => $case->id,
            'meta_key' => 'note',
            'meta_value' => 'rollback requested',
            'fix_type' => 'metadata_update',
        ], [
            'type' => 'user',
            'id' => 1,
            'company_id' => 101,
        ]);

        $this->assertSame($case->id, $fix->case_id);
        $this->assertSame('proposed', $fix->status);
    }
}
