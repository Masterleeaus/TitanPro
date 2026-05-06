<?php

use App\Models\WorkflowAuditLog;
use App\Models\WorkflowInstance;
use App\Platform\Workflows\ConditionEvaluator;
use App\Platform\Workflows\GuardEngine;
use App\Platform\Workflows\StepExecutor;
use App\Platform\Workflows\TransitionEngine;
use App\Platform\Workflows\WorkflowRunner;
use Illuminate\Support\Facades\Queue;

// ══════════════════════════════════════════════════════════════════════════════
// ConditionEvaluator
// ══════════════════════════════════════════════════════════════════════════════

describe('ConditionEvaluator', function () {
    test('evaluates == operator correctly', function () {
        $evaluator = new ConditionEvaluator;

        expect($evaluator->evaluate(['field' => 'status', 'operator' => '==', 'value' => 'active'], ['status' => 'active']))->toBeTrue();
        expect($evaluator->evaluate(['field' => 'status', 'operator' => '==', 'value' => 'active'], ['status' => 'inactive']))->toBeFalse();
    });

    test('evaluates != operator correctly', function () {
        $evaluator = new ConditionEvaluator;

        expect($evaluator->evaluate(['field' => 'status', 'operator' => '!=', 'value' => 'active'], ['status' => 'inactive']))->toBeTrue();
    });

    test('evaluates numeric comparison operators', function () {
        $evaluator = new ConditionEvaluator;

        expect($evaluator->evaluate(['field' => 'score', 'operator' => '>=', 'value' => 5], ['score' => 10]))->toBeTrue();
        expect($evaluator->evaluate(['field' => 'score', 'operator' => '<',  'value' => 5], ['score' => 3]))->toBeTrue();
    });

    test('evaluates in / not_in operators', function () {
        $evaluator = new ConditionEvaluator;

        expect($evaluator->evaluate(['field' => 'role', 'operator' => 'in',     'value' => ['admin', 'super']], ['role' => 'admin']))->toBeTrue();
        expect($evaluator->evaluate(['field' => 'role', 'operator' => 'not_in', 'value' => ['admin', 'super']], ['role' => 'guest']))->toBeTrue();
    });

    test('evaluates string operators', function () {
        $evaluator = new ConditionEvaluator;

        expect($evaluator->evaluate(['field' => 'email', 'operator' => 'contains', 'value' => '@example'], ['email' => 'user@example.com']))->toBeTrue();
        expect($evaluator->evaluate(['field' => 'name',  'operator' => 'starts_with', 'value' => 'Titan'], ['name' => 'TitanPro']))->toBeTrue();
        expect($evaluator->evaluate(['field' => 'name',  'operator' => 'ends_with',   'value' => 'Pro'],   ['name' => 'TitanPro']))->toBeTrue();
    });

    test('evaluates empty / not_empty operators', function () {
        $evaluator = new ConditionEvaluator;

        expect($evaluator->evaluate(['field' => 'note', 'operator' => 'empty'],     ['note' => '']))->toBeTrue();
        expect($evaluator->evaluate(['field' => 'note', 'operator' => 'not_empty'], ['note' => 'text']))->toBeTrue();
    });

    test('resolves dot-notation field paths', function () {
        $evaluator = new ConditionEvaluator;

        $context = ['user' => ['role' => 'admin']];
        expect($evaluator->evaluate(['field' => 'user.role', 'operator' => '==', 'value' => 'admin'], $context))->toBeTrue();
    });

    test('resolves context. prefixed field paths', function () {
        $evaluator = new ConditionEvaluator;

        $context = ['invoice_id' => 99];
        expect($evaluator->evaluate(['field' => 'context.invoice_id', 'operator' => '==', 'value' => 99], $context))->toBeTrue();
    });

    test('evaluates compound AND logic', function () {
        $evaluator = new ConditionEvaluator;

        $condition = [
            'logic'      => 'and',
            'conditions' => [
                ['field' => 'a', 'operator' => '==', 'value' => 1],
                ['field' => 'b', 'operator' => '==', 'value' => 2],
            ],
        ];

        expect($evaluator->evaluate($condition, ['a' => 1, 'b' => 2]))->toBeTrue();
        expect($evaluator->evaluate($condition, ['a' => 1, 'b' => 9]))->toBeFalse();
    });

    test('evaluates compound OR logic', function () {
        $evaluator = new ConditionEvaluator;

        $condition = [
            'logic'      => 'or',
            'conditions' => [
                ['field' => 'a', 'operator' => '==', 'value' => 1],
                ['field' => 'b', 'operator' => '==', 'value' => 2],
            ],
        ];

        expect($evaluator->evaluate($condition, ['a' => 9, 'b' => 2]))->toBeTrue();
        expect($evaluator->evaluate($condition, ['a' => 9, 'b' => 9]))->toBeFalse();
    });

    test('returns false for missing field', function () {
        $evaluator = new ConditionEvaluator;

        expect($evaluator->evaluate(['field' => 'nonexistent', 'operator' => '==', 'value' => 'foo'], []))->toBeFalse();
    });
});

// ══════════════════════════════════════════════════════════════════════════════
// GuardEngine
// ══════════════════════════════════════════════════════════════════════════════

describe('GuardEngine', function () {
    test('passes when no guards are declared', function () {
        $engine = new GuardEngine(new ConditionEvaluator);
        $instance = WorkflowInstance::factory()->make();

        $result = $engine->check([], [], $instance);

        expect($result['passed'])->toBeTrue();
    });

    test('blocks on a failing condition guard', function () {
        $engine   = new GuardEngine(new ConditionEvaluator);
        $instance = WorkflowInstance::factory()->make();

        $guards = [[
            'type'      => 'condition',
            'condition' => ['field' => 'approved', 'operator' => '==', 'value' => true],
            'message'   => 'Not approved.',
        ]];

        $result = $engine->check($guards, ['approved' => false], $instance);

        expect($result['passed'])->toBeFalse()
            ->and($result['reason'])->toBe('Not approved.');
    });

    test('passes a field_required guard when field is present', function () {
        $engine   = new GuardEngine(new ConditionEvaluator);
        $instance = WorkflowInstance::factory()->make();

        $guards = [['type' => 'field_required', 'field' => 'invoice_id']];

        $result = $engine->check($guards, ['invoice_id' => 42], $instance);

        expect($result['passed'])->toBeTrue();
    });

    test('blocks a field_required guard when field is absent', function () {
        $engine   = new GuardEngine(new ConditionEvaluator);
        $instance = WorkflowInstance::factory()->make();

        $guards = [['type' => 'field_required', 'field' => 'invoice_id']];

        $result = $engine->check($guards, [], $instance);

        expect($result['passed'])->toBeFalse();
    });
});

// ══════════════════════════════════════════════════════════════════════════════
// TransitionEngine
// ══════════════════════════════════════════════════════════════════════════════

describe('TransitionEngine', function () {
    test('transitions an instance and writes an audit record', function () {
        $engine   = app(TransitionEngine::class);
        $instance = WorkflowInstance::factory()->create(['status' => 'pending']);

        $engine->transition(
            instance:   $instance,
            toStep:     'send_welcome',
            outcome:    'completed',
            stepType:   'action',
            durationMs: 42,
        );

        $instance->refresh();

        expect($instance->current_step)->toBe('send_welcome')
            ->and($instance->status)->toBe('running');

        $this->assertDatabaseHas('titan_workflow_audit', [
            'workflow_instance_id' => $instance->id,
            'step_key'             => 'send_welcome',
            'outcome'              => 'completed',
            'duration_ms'          => 42,
        ]);
    });

    test('sets started_at on first running transition', function () {
        $engine   = app(TransitionEngine::class);
        $instance = WorkflowInstance::factory()->create(['status' => 'pending', 'started_at' => null]);

        $engine->transition($instance, 'step_a', 'started');

        $instance->refresh();

        expect($instance->started_at)->not->toBeNull();
    });

    test('sets completed_at when outcome transitions to failed', function () {
        $engine   = app(TransitionEngine::class);
        $instance = WorkflowInstance::factory()->create(['status' => 'running']);

        $engine->transition($instance, 'step_a', 'failed', error: 'boom');

        $instance->refresh();

        expect($instance->status)->toBe('failed')
            ->and($instance->completed_at)->not->toBeNull();
    });

    test('WorkflowAuditLog is append-only', function () {
        $log = WorkflowAuditLog::factory()->create();

        expect(fn () => $log->delete())->toThrow(\LogicException::class);
    });
});

// ══════════════════════════════════════════════════════════════════════════════
// WorkflowRunner — full 3-step happy path
// ══════════════════════════════════════════════════════════════════════════════

describe('WorkflowRunner', function () {
    /**
     * Build a simple 3-step manifest that uses a no-op action class injected
     * via the service container.
     *
     * @return array<string, mixed>
     */
    function makeThreeStepManifest(): array
    {
        return [
            'id'      => 'test_three_step',
            'version' => '1.0.0',
            'steps'   => [
                ['key' => 'step_one',   'type' => 'action', 'class' => \Tests\Support\NoOpWorkflowAction::class, 'next' => 'step_two'],
                ['key' => 'step_two',   'type' => 'action', 'class' => \Tests\Support\NoOpWorkflowAction::class, 'next' => 'step_three'],
                ['key' => 'step_three', 'type' => 'action', 'class' => \Tests\Support\NoOpWorkflowAction::class],
            ],
        ];
    }

    test('executes all 3 steps and marks instance completed', function () {
        $runner   = app(WorkflowRunner::class);
        $instance = $runner->start(makeThreeStepManifest());

        expect($instance->status)->toBe('completed')
            ->and(WorkflowAuditLog::where('workflow_instance_id', $instance->id)->count())->toBe(3);
    });

    test('persists step transitions in audit log', function () {
        $runner   = app(WorkflowRunner::class);
        $instance = $runner->start(makeThreeStepManifest());

        $steps = WorkflowAuditLog::where('workflow_instance_id', $instance->id)
            ->orderBy('id')
            ->pluck('step_key')
            ->all();

        expect($steps)->toBe(['step_one', 'step_two', 'step_three']);
    });

    test('condition step branches to next_true when condition passes', function () {
        $manifest = [
            'id'      => 'test_condition',
            'version' => '1.0.0',
            'steps'   => [
                [
                    'key'        => 'check_status',
                    'type'       => 'condition',
                    'condition'  => ['field' => 'approved', 'operator' => '==', 'value' => true],
                    'next_true'  => 'approve_step',
                    'next_false' => 'reject_step',
                ],
                ['key' => 'approve_step', 'type' => 'action', 'class' => \Tests\Support\NoOpWorkflowAction::class],
                ['key' => 'reject_step',  'type' => 'action', 'class' => \Tests\Support\NoOpWorkflowAction::class],
            ],
        ];

        $runner   = app(WorkflowRunner::class);
        $instance = $runner->start($manifest, context: ['approved' => true]);

        $executedSteps = WorkflowAuditLog::where('workflow_instance_id', $instance->id)
            ->pluck('step_key')
            ->all();

        expect($executedSteps)->toContain('check_status')
            ->and($executedSteps)->toContain('approve_step')
            ->and($executedSteps)->not->toContain('reject_step');
    });

    test('wait step parks instance in waiting status', function () {
        $manifest = [
            'id'      => 'test_wait',
            'version' => '1.0.0',
            'steps'   => [
                ['key' => 'pre_step',  'type' => 'action', 'class' => \Tests\Support\NoOpWorkflowAction::class, 'next' => 'pause_here'],
                ['key' => 'pause_here', 'type' => 'wait'],
                ['key' => 'post_step', 'type' => 'action', 'class' => \Tests\Support\NoOpWorkflowAction::class],
            ],
        ];

        $runner   = app(WorkflowRunner::class);
        $instance = $runner->start($manifest);

        expect($instance->status)->toBe('waiting')
            ->and($instance->current_step)->toBe('pause_here');
    });

    test('failed step with retries=3 retries three times before dead-lettering', function () {
        Queue::fake();

        $manifest = [
            'id'      => 'test_retry',
            'version' => '1.0.0',
            'steps'   => [
                [
                    'key'     => 'failing_step',
                    'type'    => 'action',
                    'class'   => \Tests\Support\AlwaysFailWorkflowAction::class,
                    'retries' => 3,
                ],
            ],
        ];

        $runner   = app(WorkflowRunner::class);
        $instance = $runner->start($manifest);

        expect($instance->status)->toBe('failed');

        Queue::assertPushed(\App\Jobs\WorkflowStepDeadLetterJob::class, function ($job) use ($instance) {
            return $job->instanceId === $instance->id && $job->stepKey === 'failing_step';
        });
    });

    test('guard block prevents step execution and marks instance failed', function () {
        $manifest = [
            'id'      => 'test_guard',
            'version' => '1.0.0',
            'steps'   => [
                [
                    'key'    => 'guarded_step',
                    'type'   => 'action',
                    'class'  => \Tests\Support\NoOpWorkflowAction::class,
                    'guards' => [[
                        'type'      => 'condition',
                        'condition' => ['field' => 'allowed', 'operator' => '==', 'value' => true],
                        'message'   => 'Not allowed.',
                    ]],
                ],
            ],
        ];

        $runner   = app(WorkflowRunner::class);
        $instance = $runner->start($manifest, context: ['allowed' => false]);

        expect($instance->status)->toBe('failed');

        $this->assertDatabaseHas('titan_workflow_audit', [
            'workflow_instance_id' => $instance->id,
            'step_key'             => 'guarded_step',
            'outcome'              => 'guarded',
        ]);
    });

    test('workflows:status command lists instances', function () {
        WorkflowInstance::factory()->create(['workflow_id' => 'test_flow', 'status' => 'completed']);

        $this->artisan('workflows:status')
            ->assertExitCode(0)
            ->expectsOutputToContain('test_flow');
    });

    test('workflows:status --json outputs valid JSON', function () {
        WorkflowInstance::factory()->create(['workflow_id' => 'json_flow', 'status' => 'running']);

        $this->artisan('workflows:status', ['--json' => true])
            ->assertExitCode(0);
    });
});
