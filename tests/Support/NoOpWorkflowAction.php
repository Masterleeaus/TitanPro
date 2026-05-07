<?php

namespace Tests\Support;

use App\Models\WorkflowInstance;

/**
 * A no-op action class used by workflow engine tests.
 * Simply returns without doing anything, simulating a successful step.
 */
class NoOpWorkflowAction
{
    public function execute(WorkflowInstance $instance, array $context): ?array
    {
        // No-op: returns null so the workflow context is unchanged.
        return null;
    }
}
