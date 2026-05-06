<?php

namespace App\Platform\Automation;

use Illuminate\Contracts\Container\Container;

/**
 * PipelineRunner executes a declared pipeline class's steps in order.
 *
 * Pipeline classes must expose either:
 *
 *   public function steps(): array   – returns an ordered list of step class FQCNs
 *                                       or step key strings for simple pipelines
 *
 * OR the pipeline can be an invokable / runnable class:
 *
 *   public function run(array $payload): mixed
 *
 * When steps() is present each step is resolved via the container and called as
 * handle(array &$payload): void — allowing steps to mutate the payload in place.
 * The final payload is returned as the pipeline output.
 *
 * Parallel execution is opt-in via the $parallel flag; in that mode all steps are
 * dispatched as independent jobs and the runner returns immediately with a
 * ['parallel' => true] marker so the caller can record the intent.
 */
class PipelineRunner
{
    public function __construct(private readonly Container $container) {}

    /**
     * Run the pipeline for the given automation definition.
     *
     * @param array $automation Automation definition (must include 'pipeline' key)
     * @param array $payload    Initial payload passed through the pipeline
     * @param bool  $parallel   When true each step is dispatched independently
     * @return mixed            Mutated payload or pipeline run() return value
     *
     * @throws \RuntimeException When no pipeline class is configured or does not exist
     */
    public function run(array $automation, array $payload = [], bool $parallel = false): mixed
    {
        $pipelineClass = $automation['pipeline'] ?? null;

        if (! $pipelineClass) {
            throw new \RuntimeException(
                "Automation [{$automation['id']}] has no pipeline configured."
            );
        }

        if (! class_exists($pipelineClass)) {
            throw new \RuntimeException(
                "Pipeline class [{$pipelineClass}] does not exist."
            );
        }

        $pipeline = $this->container->make($pipelineClass);

        // If the pipeline exposes a run() method, delegate directly.
        if (method_exists($pipeline, 'run')) {
            return $pipeline->run($payload);
        }

        // Otherwise expect steps() to return an ordered list of step classes.
        if (! method_exists($pipeline, 'steps')) {
            throw new \RuntimeException(
                "Pipeline [{$pipelineClass}] must implement run() or steps()."
            );
        }

        $steps = $pipeline->steps();

        if ($parallel) {
            return $this->runParallel($steps, $payload);
        }

        return $this->runSequential($steps, $payload);
    }

    /**
     * Execute steps sequentially, passing the mutated payload through each step.
     */
    private function runSequential(array $steps, array $payload): array
    {
        foreach ($steps as $stepClass) {
            if (! class_exists($stepClass)) {
                // Skip string-only step names (legacy declarative pipelines).
                continue;
            }

            $step = $this->container->make($stepClass);

            if (method_exists($step, 'handle')) {
                $step->handle($payload);
            }
        }

        return $payload;
    }

    /**
     * Dispatch each step as an independent queued job (fire-and-forget).
     *
     * Returns a marker so callers know parallel dispatch was used.
     */
    private function runParallel(array $steps, array $payload): array
    {
        foreach ($steps as $stepClass) {
            if (! class_exists($stepClass)) {
                continue;
            }

            dispatch(function () use ($stepClass, $payload) {
                $step = app($stepClass);
                if (method_exists($step, 'handle')) {
                    $step->handle($payload);
                }
            })->afterResponse();
        }

        return ['parallel' => true, 'steps' => count($steps)];
    }
}
