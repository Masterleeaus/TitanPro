<?php

namespace Modules\Security\Tests\Unit;

use Modules\Security\Services\Core\SecurityModuleService;
use Tests\TestCase;

class SecurityModuleServiceTest extends TestCase
{
    /** @test */
    public function service_returns_feature_configuration(): void
    {
        $service = new SecurityModuleService();

        $this->assertIsArray($service->features());
    }
}
