<?php

namespace Modules\GroundZeroOps\Tests\Feature;

use Modules\GroundZeroOps\Events\IncidentLogged;
use Modules\GroundZeroOps\Events\JobAssigned;
use Modules\GroundZeroOps\Events\ShiftEnded;
use Modules\GroundZeroOps\Events\ShiftStarted;
use PHPUnit\Framework\TestCase;

class SignalEnvelopeTest extends TestCase
{
    public function test_all_ground_zero_events_include_required_signal_fields(): void
    {
        $events = [
            new JobAssigned(1, 7, 101, ['job_id' => 10]),
            new ShiftStarted(1, 7, 102, ['shift_id' => 11]),
            new ShiftEnded(1, 7, 103, ['shift_id' => 12]),
            new IncidentLogged(1, 7, 104, ['incident_id' => 13]),
        ];

        foreach ($events as $event) {
            $envelope = $event->signalEnvelope();
            $this->assertArrayHasKey('signal_name', $envelope);
            $this->assertArrayHasKey('company_id', $envelope);
            $this->assertArrayHasKey('actor_id', $envelope);
            $this->assertArrayHasKey('source_type', $envelope);
            $this->assertArrayHasKey('source_id', $envelope);
            $this->assertArrayHasKey('occurred_at', $envelope);
            $this->assertArrayHasKey('payload', $envelope);
            $this->assertArrayHasKey('risk_level', $envelope);
        }
    }
}
