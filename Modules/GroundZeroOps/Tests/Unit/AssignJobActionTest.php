<?php

namespace Modules\GroundZeroOps\Tests\Unit;

use Modules\GroundZeroOps\Actions\AssignJobAction;
use PHPUnit\Framework\TestCase;

class AssignJobActionTest extends TestCase
{
    public function test_assign_job_action_class_and_execute_method_exist(): void
    {
        $this->assertTrue(class_exists(AssignJobAction::class));
        $this->assertTrue(method_exists(AssignJobAction::class, 'execute'));
    }
}
