<?php

namespace App\Listeners;

use Illuminate\Queue\Events\JobFailed;
use Illuminate\Support\Facades\Log;

class AlertOnFailedMailJob
{
    public function handle(JobFailed $event): void
    {
        if ($event->job->getQueue() !== 'mail') {
            return;
        }

        Log::channel('ops')->critical('[mail-queue] Job failed', [
            'job'        => $event->job->resolveName(),
            'queue'      => $event->job->getQueue(),
            'connection' => $event->connectionName,
            'exception'  => $event->exception?->getMessage() ?? 'No exception message',
            'payload'    => $event->job->payload(),
        ]);
    }
}
