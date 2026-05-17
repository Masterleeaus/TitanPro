<?php

namespace Modules\InstantAds\Tests\Unit;

use Modules\InstantAds\Actions\CreateBatchVariantsAction;
use PHPUnit\Framework\TestCase;

class CreateBatchVariantsActionTest extends TestCase
{
    public function test_execute_generates_expected_variant_count(): void
    {
        $action = new CreateBatchVariantsAction;

        $result = $action->execute([
            'prompt' => 'Modern kitchen cleaning ad',
            'count' => 3,
        ]);

        $this->assertCount(3, $result['variants']);
        $this->assertSame('Modern kitchen cleaning ad (variant 1)', $result['variants'][0]['prompt']);
    }
}
