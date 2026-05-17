<?php

namespace Modules\TitanEchoAssist\Tests\Unit;

use Modules\TitanCore\AI\Adapters\AnthropicClient;
use Modules\TitanCore\AI\Adapters\OpenAIClient;
use PHPUnit\Framework\TestCase;

class TitanCoreAdapterClientsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        \TitanChatbotHttpStub::resetFake();
        \TitanChatbotConfigStub::reset();
    }

    public function test_openai_client_chat_posts_expected_headers_and_body(): void
    {
        \TitanChatbotConfigStub::set([
            'ai.providers.openai.api_key' => 'sk-openai-test',
            'ai.providers.openai.model' => 'gpt-4o-mini',
        ]);

        \TitanChatbotHttpStub::fakeForUrl('https://api.openai.com/v1/chat/completions', [
            'choices' => [['message' => ['content' => 'hello']]],
            'usage' => ['prompt_tokens' => 10, 'completion_tokens' => 5, 'total_tokens' => 15],
        ]);

        $result = (new OpenAIClient())->chat([
            ['role' => 'user', 'content' => 'ping'],
        ], ['stream' => true]);

        $request = \TitanChatbotHttpStub::lastRequest();

        $this->assertTrue($result['ok']);
        $this->assertSame('hello', $result['content']);
        $this->assertSame('https://api.openai.com/v1/chat/completions', $request['url']);
        $this->assertSame('Bearer sk-openai-test', $request['headers']['Authorization'] ?? null);
        $this->assertSame('gpt-4o-mini', $request['body']['model'] ?? null);
        $this->assertSame('ping', $request['body']['messages'][0]['content'] ?? null);
        $this->assertTrue($request['body']['stream'] ?? false);
    }

    public function test_anthropic_client_chat_posts_expected_headers_and_body(): void
    {
        \TitanChatbotConfigStub::set([
            'ai.providers.anthropic.api_key' => 'anthropic-test',
            'ai.providers.anthropic.model' => 'claude-3-haiku-20240307',
        ]);

        \TitanChatbotHttpStub::fakeForUrl('https://api.anthropic.com/v1/messages', [
            'content' => [['type' => 'text', 'text' => 'hello']],
            'usage' => ['input_tokens' => 8, 'output_tokens' => 3],
        ]);

        $result = (new AnthropicClient())->chat([
            ['role' => 'system', 'content' => 'be helpful'],
            ['role' => 'user', 'content' => 'ping'],
        ], ['max_tokens' => 256]);

        $request = \TitanChatbotHttpStub::lastRequest();

        $this->assertTrue($result['ok']);
        $this->assertSame('hello', $result['content']);
        $this->assertSame('https://api.anthropic.com/v1/messages', $request['url']);
        $this->assertSame('anthropic-test', $request['headers']['x-api-key'] ?? null);
        $this->assertSame('2023-06-01', $request['headers']['anthropic-version'] ?? null);
        $this->assertSame('claude-3-haiku-20240307', $request['body']['model'] ?? null);
        $this->assertSame(256, $request['body']['max_tokens'] ?? null);
        $this->assertSame('be helpful', $request['body']['system'] ?? null);
        $this->assertCount(1, $request['body']['messages'] ?? []);
    }
}

