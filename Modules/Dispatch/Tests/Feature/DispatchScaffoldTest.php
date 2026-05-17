<?php

declare(strict_types=1);

namespace Modules\Dispatch\Tests\Feature;

use Tests\TestCase;

class DispatchScaffoldTest extends TestCase
{
    public function test_dispatch_feature_config_exists(): void
    {
        $this->assertFileExists(module_path('Dispatch', 'Config/features.php'));
    }
}
