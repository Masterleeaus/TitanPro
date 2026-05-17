<?php

namespace Modules\GroundZeroOps\Listeners;

use Modules\GroundZeroOps\Jobs\PublishGroundZeroSignal;

class QueueSignalEnvelopeListener
{
    public function handle(object $event): void
    {
        if (! method_exists($event, 'signalEnvelope')) {
            return;
        }

        /** @var array<string, mixed> $envelope */
        $envelope = $event->signalEnvelope();

        PublishGroundZeroSignal::dispatch((int) $envelope['company_id'], $envelope);
    }
}
