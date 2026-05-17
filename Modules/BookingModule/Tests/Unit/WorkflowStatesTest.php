<?php

namespace Modules\BookingModule\Tests\Unit;

use Modules\BookingModule\Workflows\BookingLifecycleWorkflow;
use Tests\TestCase;

class WorkflowStatesTest extends TestCase
{
    public function test_workflow_stages_include_full_booking_lifecycle(): void
    {
        $workflow = new BookingLifecycleWorkflow();

        $this->assertSame(
            ['draft', 'pending_approval', 'confirmed', 'dispatched', 'in_progress', 'completed', 'invoiced', 'paid', 'cancelled', 'rescheduled', 'no_show'],
            $workflow->stages()
        );
    }
}
