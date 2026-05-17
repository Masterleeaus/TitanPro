<?php

namespace Modules\Complaint\Tests\Feature;

use Modules\Complaint\Actions\CreateComplaintAction;
use Modules\Complaint\Actions\EscalateComplaintAction;
use Modules\Complaint\Actions\ResolveComplaintAction;
use PHPUnit\Framework\TestCase;

class ComplaintAiEscalationFlowTest extends TestCase
{
    public function test_required_action_classes_exist_for_create_escalate_resolve_flow(): void
    {
        $this->assertTrue(class_exists(CreateComplaintAction::class));
        $this->assertTrue(class_exists(EscalateComplaintAction::class));
        $this->assertTrue(class_exists(ResolveComplaintAction::class));
    }
}
