<?php

namespace Modules\TitanNexus\Listeners\Voice;

use Modules\TitanNexus\Events\Voice\VoiceWebhookReceived;
use Modules\TitanNexus\Jobs\Voice\FetchVoiceRecordingJob;

class QueueVoiceRecordingFetch
{
    public function handle(VoiceWebhookReceived $event): void
    {
        $sid = $event->payload['CallSid'] ?? null;
        if ($sid) {
            FetchVoiceRecordingJob::dispatch($sid);
        }
    }
}
