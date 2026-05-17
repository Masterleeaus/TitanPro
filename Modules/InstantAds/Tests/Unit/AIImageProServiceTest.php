<?php

namespace Modules\InstantAds\Tests\Unit;

use Modules\InstantAds\Actions\GenerateAdImageAction;
use Modules\InstantAds\Services\AIImageProService;
use PHPUnit\Framework\TestCase;

class AIImageProServiceTest extends TestCase
{
    public function test_dispatch_image_generation_job_delegates_to_action(): void
    {
        $action = new class extends GenerateAdImageAction
        {
            public array $captured = [];

            public function dispatch(array $params, ?int $userId, mixed $driver): int
            {
                $this->captured = ['params' => $params, 'userId' => $userId, 'driver' => $driver];

                return 42;
            }
        };

        $service = new AIImageProService($action);
        $result = $service->dispatchImageGenerationJob(['prompt' => 'x'], 8, null);

        $this->assertSame(42, $result);
        $this->assertSame(8, $action->captured['userId']);
        $this->assertSame(['prompt' => 'x'], $action->captured['params']);
    }
}
