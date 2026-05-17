<?php

namespace Modules\InstantAds\Tests\Unit;

use Modules\InstantAds\Actions\GenerateAdImageAction;
use PHPUnit\Framework\TestCase;

class GenerateAdImageActionTest extends TestCase
{
    public function test_execute_returns_array_payload_shape(): void
    {
        $action = new GenerateAdImageAction;

        $result = $action->execute([
            'prompt' => 'Fresh summer ad creative',
            'model' => 'dall-e-3',
            'url' => '/uploads/sample.png',
        ]);

        $this->assertSame('/uploads/sample.png', $result['url']);
        $this->assertSame('dalle', $result['provider']);
        $this->assertSame('Fresh summer ad creative', $result['prompt']);
    }
}
