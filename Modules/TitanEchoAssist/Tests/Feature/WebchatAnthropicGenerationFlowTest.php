<?php

namespace Modules\TitanEchoAssist\Tests\Feature;

use Modules\TitanEchoAssist\Services\ConversationStateStore;
use Modules\TitanEchoAssist\Services\GeneratorBridge;
use Modules\TitanEchoAssist\Services\WebchatChannel;
use PHPUnit\Framework\TestCase;

class WebchatAnthropicGenerationFlowTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        \TitanChatbotHttpStub::resetFake();
        \TitanChatbotConfigStub::reset();
        \TitanChatbotCacheStub::flush();
    }

    public function test_webchat_channel_generates_reply_through_generator_bridge_and_anthropic(): void
    {
        \TitanChatbotConfigStub::set([
            'titan-chatbot.ai.provider' => 'anthropic',
            'titan-chatbot.ai.anthropic.key' => 'anthropic-key',
            'titan-chatbot.ai.anthropic.model' => 'claude-3-haiku-20240307',
        ]);

        \TitanChatbotHttpStub::fakeForUrl('https://api.anthropic.com/v1/messages', [
            'content' => [['type' => 'text', 'text' => 'Anthropic final reply']],
            'usage' => ['input_tokens' => 11, 'output_tokens' => 4],
        ]);

        $channel = new WebchatChannel(new GeneratorBridge(), new ConversationStateStore());

        $reply = $channel->handle([
            'chatbot_id' => 42,
            'session_id' => 'sess-1',
            'message' => 'Hello there',
            'chatbot' => null,
        ]);

        $this->assertSame('Anthropic final reply', $reply);
    }
}
