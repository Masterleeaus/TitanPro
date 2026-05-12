<?php

namespace Modules\TitanRewind\Tests\Integration;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Modules\Accountings\Events\InvoiceJournalPosted;
use Modules\TitanRewind\Models\RewindEvent;
use Tests\TestCase;

class SnapshotCaptureListenerTest extends TestCase
{
    use RefreshDatabase;

    public function test_snapshot_is_captured_for_titancore_and_accountings_events(): void
    {
        Event::dispatch('TitanCore.AuditEventLogged', [[
            'company_id' => 601,
            'entity_type' => 'journal',
            'entity_id' => 'J-100',
            'changes' => ['status' => 'updated'],
        ]]);

        Event::dispatch(new InvoiceJournalPosted([
            'company_id' => 601,
            'journal_id' => 88,
            'reference' => 'INV-88',
        ]));

        $this->assertDatabaseHas('titan_rewind_events', [
            'company_id' => 601,
            'event_type' => 'snapshot_captured',
        ]);

        $this->assertGreaterThanOrEqual(2, RewindEvent::query()->where('company_id', 601)->count());
    }
}
