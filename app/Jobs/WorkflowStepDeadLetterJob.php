<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

/**
 * Dead-letter job for workflow steps that have exhausted their retry budget.
 *
 * When dispatched, this job logs a structured error entry so operators can
 * inspect and manually replay or dismiss failed steps.
 *
 * The job itself does not retry (it IS the dead-letter sink).
 *
 * ## Queue configuration
 *
 * Dead-letter jobs are pushed to the queue named by `config('queue.dead_letter_queue')`,
 * which defaults to `"dead-letter"`. Configure a dedicated dead-letter queue in your
 * queue driver (e.g., SQS, Redis) and add the following to `config/queue.php`:
 *
 *   'dead_letter_queue' => env('QUEUE_DEAD_LETTER', 'dead-letter'),
 *
 * A supervisor worker should drain this queue for manual inspection or replay.
 */
class WorkflowStepDeadLetterJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** No retries — this is already the failure handler. */
    public int $tries = 1;

    public function __construct(
        public readonly int    $instanceId,
        public readonly string $stepKey,
        public readonly string $error,
        public readonly array  $stepDefinition = [],
    ) {}

    public function handle(): void
    {
        Log::error('WorkflowStepDeadLetterJob: step failed after exhausting retries.', [
            'workflow_instance_id' => $this->instanceId,
            'step_key'             => $this->stepKey,
            'error'                => $this->error,
            'step_definition'      => $this->stepDefinition,
        ]);
    }

    /**
     * The queue to use for dead-letter jobs.
     * Operators can configure a dedicated dead-letter queue in their queue driver.
     */
    public function queue(): string
    {
        return config('queue.dead_letter_queue', 'dead-letter');
    }
}
