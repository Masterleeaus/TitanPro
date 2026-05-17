<?php

namespace Modules\GroundZeroOps\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\GroundZeroOps\Events\IncidentLogged;
use Modules\GroundZeroOps\Events\JobAssigned;
use Modules\GroundZeroOps\Events\ShiftEnded;
use Modules\GroundZeroOps\Events\ShiftStarted;
use Modules\GroundZeroOps\Listeners\QueueSignalEnvelopeListener;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        JobAssigned::class => [
            QueueSignalEnvelopeListener::class,
        ],
        ShiftStarted::class => [
            QueueSignalEnvelopeListener::class,
        ],
        ShiftEnded::class => [
            QueueSignalEnvelopeListener::class,
        ],
        IncidentLogged::class => [
            QueueSignalEnvelopeListener::class,
        ],
    ];
}
