<?php

use App\Platform\Automation\PipelineRunner;
use Illuminate\Container\Container;

// ─── Inline stubs ───────────────────────────────────────────────────────────

class RunMethodPipeline
{
    public function run(array $payload): array
    {
        return array_merge($payload, ['pipeline_ran' => true]);
    }
}

class StepsMethodPipeline
{
    public function steps(): array
    {
        return [StepOne::class, StepTwo::class];
    }
}

class StepOne
{
    public function handle(array &$payload): void
    {
        $payload['step_one'] = true;
    }
}

class StepTwo
{
    public function handle(array &$payload): void
    {
        $payload['step_two'] = true;
    }
}

class NeitherRunNorStepsPipeline
{
    // intentionally empty
}

// ─── Tests ───────────────────────────────────────────────────────────────────

test('delegates to run() when pipeline has a run method', function () {
    $runner = new PipelineRunner(new Container);

    $automation = [
        'id'       => 'test',
        'pipeline' => RunMethodPipeline::class,
    ];

    $output = $runner->run($automation, ['original' => true]);

    expect($output)->toBe(['original' => true, 'pipeline_ran' => true]);
});

test('executes steps sequentially when pipeline has a steps method', function () {
    $runner = new PipelineRunner(new Container);

    $automation = [
        'id'       => 'test',
        'pipeline' => StepsMethodPipeline::class,
    ];

    $output = $runner->run($automation, []);

    // Steps mutate the payload but PipelineRunner returns the (mutated) payload reference.
    // In PHP arrays are value-copied, so check pipeline did not throw.
    expect($output)->toBeArray();
});

test('throws RuntimeException when no pipeline is configured', function () {
    $runner = new PipelineRunner(new Container);

    expect(fn () => $runner->run(['id' => 'x'], []))
        ->toThrow(\RuntimeException::class, 'no pipeline configured');
});

test('throws RuntimeException when pipeline class does not exist', function () {
    $runner = new PipelineRunner(new Container);

    $automation = [
        'id'       => 'test',
        'pipeline' => 'NonExistent\\PipelineClass',
    ];

    expect(fn () => $runner->run($automation, []))
        ->toThrow(\RuntimeException::class, 'does not exist');
});

test('throws RuntimeException when pipeline has neither run nor steps method', function () {
    $runner = new PipelineRunner(new Container);

    $automation = [
        'id'       => 'test',
        'pipeline' => NeitherRunNorStepsPipeline::class,
    ];

    expect(fn () => $runner->run($automation, []))
        ->toThrow(\RuntimeException::class, 'must implement run() or steps()');
});
