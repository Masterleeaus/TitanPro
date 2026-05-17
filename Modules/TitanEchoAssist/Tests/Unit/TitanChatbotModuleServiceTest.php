<?php
namespace Modules\TitanEchoAssist\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Modules\TitanEchoAssist\Services\TitanChatbotModuleService;

class TitanChatbotModuleServiceTest extends TestCase
{
    public function testSummaryHasModuleName(): void
    {
        $this->assertSame('TitanChatbot', (new TitanChatbotModuleService())->summary()['module']);
    }
}
