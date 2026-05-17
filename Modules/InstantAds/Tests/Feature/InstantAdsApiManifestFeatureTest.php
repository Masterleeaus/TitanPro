<?php

namespace Modules\InstantAds\Tests\Feature;

use PHPUnit\Framework\TestCase;

class InstantAdsApiManifestFeatureTest extends TestCase
{
    public function test_generation_and_batch_routes_are_declared_in_api_routes_file(): void
    {
        $routes = (string) file_get_contents(dirname(__DIR__, 2) . '/Routes/api.php');

        $this->assertStringContainsString("/generate", $routes);
        $this->assertStringContainsString("/batch-variants", $routes);
        $this->assertStringContainsString("/settings", $routes);
    }
}
