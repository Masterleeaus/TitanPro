<?php

namespace Modules\TitanNexus\Jobs\Voice;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class FetchVoiceRecordingJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public string $callSid) {}

    public function handle(): void
    {
        // Adapter slot for imported FetchCallRecordingJob.
    }
}
