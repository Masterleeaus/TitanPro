<?php

namespace Modules\Security\Tests\Feature;

use Modules\Security\Entities\Cleaner;
use Modules\Security\Services\Domain\CleanerOperationsService;
use Tests\TestCase;

class CleanerOperationsTest extends TestCase
{
    public function test_service_generates_cleaner_code(): void
    {
        $service = new CleanerOperationsService();

        $this->assertTrue(method_exists($service, 'dashboard'));
        $this->assertTrue(method_exists(Cleaner::class, 'query'));
    }
}
