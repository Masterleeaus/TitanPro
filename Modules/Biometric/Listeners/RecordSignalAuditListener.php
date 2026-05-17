<?php

namespace Modules\Biometric\Listeners;

use Illuminate\Support\Facades\Log;

class RecordSignalAuditListener
{
    public function handle(object $event): void
    {
        Log::info('[Biometric] Signal audit', [
            'event' => $event::class,
            'payload' => get_object_vars($event),
        ]);
    }
}

