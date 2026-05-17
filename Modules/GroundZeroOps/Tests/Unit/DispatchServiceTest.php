<?php

namespace Modules\GroundZeroOps\Tests\Unit;

use Modules\GroundZeroOps\Services\DispatchService;
use PHPUnit\Framework\TestCase;

class DispatchServiceTest extends TestCase
{
    public function test_dispatch_service_methods_exist(): void
    {
        $this->assertTrue(class_exists(DispatchService::class));
        $this->assertTrue(method_exists(DispatchService::class, 'assign'));
        $this->assertTrue(method_exists(DispatchService::class, 'rankedTechniciansForJob'));
    }
}
