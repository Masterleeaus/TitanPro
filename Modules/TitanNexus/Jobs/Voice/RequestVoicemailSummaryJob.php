<?php

namespace Modules\TitanNexus\Jobs\Voice;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class RequestVoicemailSummaryJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public array $callPayload) {}

    public function handle(): void
    {
        // Adapter slot for Titan Zero voicemail-summary handoff.
    }
}
