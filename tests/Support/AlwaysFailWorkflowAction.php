<?php

namespace Tests\Support;

use App\Models\WorkflowInstance;

/**
 * A workflow action that always throws an exception.
 * Used to test the retry and dead-letter queue behaviour of StepExecutor.
 */
class AlwaysFailWorkflowAction
{
    public function execute(WorkflowInstance $instance, array $context): never
    {
        throw new \RuntimeException('AlwaysFailWorkflowAction: intentional test failure.');
    }
}
