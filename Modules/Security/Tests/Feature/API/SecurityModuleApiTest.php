<?php

namespace Modules\Security\Tests\Feature\API;

use Tests\TestCase;

class SecurityModuleApiTest extends TestCase
{
    /** @test */
    public function security_api_routes_are_registered_in_module_file(): void
    {
        $routes = file_get_contents(module_path('Security', 'Routes/api.php'));

        $this->assertStringContainsString('security/dashboard', $routes);
        $this->assertStringContainsString('security/health', $routes);
        $this->assertStringContainsString('security/features', $routes);
        $this->assertStringContainsString('security/permissions', $routes);
    }
}
