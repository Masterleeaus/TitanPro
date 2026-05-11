<?php

namespace App\Extensions\TitanPulse\System\Core\PulseKernel\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Extensions\TitanPulse\System\Core\PulseKernel\Services\WorkflowEngine;

class RunWorkflowJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public string $triggerKey,
        public array $payload,
        public array $meta = []
    ) {}

    public function handle(WorkflowEngine $engine): void
    {
        $engine->run($this->triggerKey, $this->payload, $this->meta);
    }
}
