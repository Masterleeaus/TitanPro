<?php

namespace Modules\TitanEchoAssist\Tests\Unit;

use Modules\TitanEchoAssist\Services\GeneratorBridge;
use PHPUnit\Framework\TestCase;

class GeneratorBridgeAnthropicRoutingTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        \TitanChatbotHttpStub::resetFake();
        \TitanChatbotConfigStub::reset();
    }

    public function test_generate_routes_anthropic_provider_to_anthropic_generator(): void
    {
        \TitanChatbotConfigStub::set([
            'titan-chatbot.ai.provider' => 'anthropic',
            'titan-chatbot.ai.anthropic.key' => 'anthropic-key',
            'titan-chatbot.ai.anthropic.model' => 'claude-3-haiku-20240307',
        ]);

        \TitanChatbotHttpStub::fakeForUrl('https://api.anthropic.com/v1/messages', [
            'content' => [['type' => 'text', 'text' => 'routed via anthropic']],
            'usage' => ['input_tokens' => 5, 'output_tokens' => 2],
        ]);

        $reply = (new GeneratorBridge())->generate('hello', []);
        $request = \TitanChatbotHttpStub::lastRequest();

        $this->assertSame('routed via anthropic', $reply);
        $this->assertSame('https://api.anthropic.com/v1/messages', $request['url'] ?? null);
    }
}

