<?php

namespace Modules\Biometric\Tests\Unit;

use Modules\Biometric\Actions\RecordAttendanceAction;
use Modules\Biometric\Entities\BiometricAttendance;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;

class RecordAttendanceActionTest extends TestCase
{
    public function test_execute_method_signature_matches_contract(): void
    {
        $method = new ReflectionMethod(RecordAttendanceAction::class, 'execute');

        $this->assertCount(1, $method->getParameters());
        $this->assertSame('array', $method->getParameters()[0]->getType()?->getName());
        $this->assertSame(BiometricAttendance::class, $method->getReturnType()?->getName());
    }
}

