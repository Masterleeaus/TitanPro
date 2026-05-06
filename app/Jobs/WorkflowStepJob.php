<?php

namespace App\Jobs;

use App\Models\WorkflowInstance;
use App\Platform\Workflows\StepExecutor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Dispatched by StepExecutor to run a child step inside a "parallel" parent step.
 *
 * Each child step runs in its own queue job so they can execute concurrently.
 */
class WorkflowStepJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    /**
     * @param array<string, mixed> $stepDefinition  The child step definition from the manifest.
     * @param int                  $instanceId       The parent workflow instance ID.
     */
    public function __construct(
        public readonly array $stepDefinition,
        public readonly int   $instanceId,
    ) {}

    public function handle(StepExecutor $executor): void
    {
        /** @var WorkflowInstance|null $instance */
        $instance = WorkflowInstance::find($this->instanceId);

        if (! $instance) {
            return;
        }

        $executor->execute($this->stepDefinition, $instance);
    }
}
