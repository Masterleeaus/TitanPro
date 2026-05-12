<?php

namespace Modules\BookingModule\Tests\Unit;

use Modules\BookingModule\Workflows\BookingLifecycleWorkflow;
use Tests\TestCase;

class WorkflowGuardRulesTest extends TestCase
{
    public function test_workflow_guard_rules_include_required_transition_guards(): void
    {
        $workflow = new BookingLifecycleWorkflow();
        $guards = $workflow->guardRules();

        $this->assertArrayHasKey('confirmed->dispatched', $guards);
        $this->assertArrayHasKey('completed->invoiced', $guards);
    }
}

