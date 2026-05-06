<?php

namespace App\Jobs;

use App\Events\AutomationFailed;
use App\Models\AutomationRun;
use App\Platform\Automation\HandlerExecutor;
use App\Platform\Automation\PipelineRunner;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * ExecuteAutomationJob
 *
 * Resolves and executes the handler (and optional pipeline) declared in an
 * automation definition. On success the run record is marked completed. On
 * failure the attempt counter is incremented; when max_attempts is reached the
 * run is marked failed and an AutomationFailed event is dispatched.
 *
 * Retry back-off uses an exponential strategy: delay = retry_after * 2^(attempt - 1)
 * capped at 3 600 seconds (one hour).
 */
class ExecuteAutomationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Maximum execution time in seconds.
     */
    public int $timeout = 120;

    /**
     * Maximum retry delay cap in seconds (one hour).
     */
    private const MAX_RETRY_DELAY = 3600;

    /**
     * Laravel will call failed() rather than auto-retrying — we handle our own
     * retry logic through explicit re-dispatch so we can persist attempt state.
     */
    public int $tries = 1;

    public function __construct(
        public readonly int   $runId,
        public readonly array $automation,
    ) {}

    public function handle(HandlerExecutor $executor, PipelineRunner $pipelineRunner): void
    {
        $run = AutomationRun::find($this->runId);

        if (! $run) {
            return;
        }

        $run->increment('attempts');
        $run->markRunning();

        try {
            $output = null;

            // Execute the handler if one is declared.
            if (! empty($this->automation['handler'])) {
                $output = $executor->execute($this->automation, $run->payload ?? []);
            }

            // Run the pipeline if one is declared.
            if (! empty($this->automation['pipeline'])) {
                $pipelineOutput = $pipelineRunner->run(
                    $this->automation,
                    is_array($output) ? $output : ($run->payload ?? []),
                );

                $output = $pipelineOutput;
            }

            $run->markCompleted($output);
        } catch (\Throwable $e) {
            $this->handleFailure($run, $e);
        }
    }

    /**
     * Determine whether to retry or mark the run as permanently failed.
     */
    private function handleFailure(AutomationRun $run, \Throwable $e): void
    {
        if ($run->hasExceededMaxAttempts()) {
            $run->markFailed($e->getMessage());
            AutomationFailed::dispatch($run, $e->getMessage());
            return;
        }

        // Exponential back-off: base_delay * 2^(attempt - 1), max 3600 s.
        $base  = $this->automation['retry_after'] ?? 60;
        $delay = (int) min($base * (2 ** ($run->attempts - 1)), self::MAX_RETRY_DELAY);

        $run->update([
            'status'    => AutomationRun::STATUS_QUEUED,
            'exception' => $e->getMessage(),
        ]);

        static::dispatch($this->runId, $this->automation)->delay(now()->addSeconds($delay));
    }
}
