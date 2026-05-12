<?php

namespace Modules\GroundZeroOps\Tests\Integration;

use Modules\GroundZeroOps\Events\IncidentLogged;
use Modules\GroundZeroOps\Events\JobAssigned;
use Modules\GroundZeroOps\Events\ShiftEnded;
use Modules\GroundZeroOps\Events\ShiftStarted;
use Modules\GroundZeroOps\Listeners\QueueSignalEnvelopeListener;
use Modules\GroundZeroOps\Providers\EventServiceProvider;
use PHPUnit\Framework\TestCase;

class QueueListenerWiringTest extends TestCase
{
    public function test_event_service_provider_wires_signal_listener(): void
    {
        $listen = (new \ReflectionClass(EventServiceProvider::class))
            ->getDefaultProperties()['listen'] ?? [];

        $this->assertSame([QueueSignalEnvelopeListener::class], $listen[JobAssigned::class] ?? []);
        $this->assertSame([QueueSignalEnvelopeListener::class], $listen[ShiftStarted::class] ?? []);
        $this->assertSame([QueueSignalEnvelopeListener::class], $listen[ShiftEnded::class] ?? []);
        $this->assertSame([QueueSignalEnvelopeListener::class], $listen[IncidentLogged::class] ?? []);
    }
}
