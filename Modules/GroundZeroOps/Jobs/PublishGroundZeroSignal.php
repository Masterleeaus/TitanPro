<?php

namespace Modules\GroundZeroOps\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PublishGroundZeroSignal implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * @param  array<string, mixed>  $signalEnvelope
     */
    public function __construct(
        public readonly int $companyId,
        public readonly array $signalEnvelope,
    ) {}

    public function handle(): void
    {
        event('GroundZeroOps.SignalProduced', [
            'company_id' => $this->companyId,
            'signal' => $this->signalEnvelope,
        ]);
    }
}
