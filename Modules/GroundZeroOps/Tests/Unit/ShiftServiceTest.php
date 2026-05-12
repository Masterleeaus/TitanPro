<?php

namespace Modules\GroundZeroOps\Tests\Unit;

use Modules\GroundZeroOps\Services\ShiftService;
use PHPUnit\Framework\TestCase;

class ShiftServiceTest extends TestCase
{
    public function test_shift_service_methods_exist(): void
    {
        $this->assertTrue(class_exists(ShiftService::class));
        $this->assertTrue(method_exists(ShiftService::class, 'start'));
        $this->assertTrue(method_exists(ShiftService::class, 'end'));
    }
}
