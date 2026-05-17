<?php

namespace Modules\Complaint\Tests\Unit;

use Modules\Complaint\Actions\EscalateComplaintAction;
use Modules\Complaint\Entities\Complaint;
use PHPUnit\Framework\TestCase;

class EscalateComplaintActionTest extends TestCase
{
    public function test_it_does_not_escalate_resolved_complaint(): void
    {
        $action = new EscalateComplaintAction();

        $complaint = new Complaint();
        $complaint->status = 'resolved';

        $this->expectException(\LogicException::class);
        $action->execute($complaint, 'Need escalation');
    }
}
