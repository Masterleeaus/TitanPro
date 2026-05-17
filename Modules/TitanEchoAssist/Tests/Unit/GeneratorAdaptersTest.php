<?php

namespace Modules\TitanEchoAssist\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Modules\TitanEchoAssist\Services\Generators\AnthropicGenerator;
use Modules\TitanEchoAssist\Services\Generators\GeminiGenerator;
use Modules\TitanEchoAssist\Services\Generators\OpenAIGenerator;
use Modules\TitanEchoAssist\Services\Generators\GeneratorFactory;
use Modules\TitanEchoAssist\Services\Generators\Contracts\GeneratorInterface;

/**
 * Unit tests for the multi-provider generator adapters.
 *
 * All HTTP calls are intercepted by the TitanChatbotHttpStub defined in
 * Tests/bootstrap.php, so no real network requests are made.
 */
class GeneratorAdaptersTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Reset any fake HTTP state between tests
        \TitanChatbotHttpStub::resetFake();
    }

    // ── Interface compliance ─────────────────────────────────────────────────

    public function test_openai_generator_implements_interface(): void
    {
        $this->assertInstanceOf(GeneratorInterface::class, new OpenAIGenerator());
    }

    public function test_anthropic_generator_implements_interface(): void
    {
        $this->assertInstanceOf(GeneratorInterface::class, new AnthropicGenerator());
    }

    public function test_gemini_generator_implements_interface(): void
    {
        $this->assertInstanceOf(GeneratorInterface::class, new GeminiGenerator());
    }

    // ── getName() ────────────────────────────────────────────────────────────

    public function test_openai_generator_name(): void
    {
        $this->assertSame('openai', (new OpenAIGenerator())->getName());
    }

    public function test_anthropic_generator_name(): void
    {
        $this->assertSame('anthropic', (new AnthropicGenerator())->getName());
    }

    public function test_gemini_generator_name(): void
    {
        $this->assertSame('gemini', (new GeminiGenerator())->getName());
    }

    // ── isAvailable() ────────────────────────────────────────────────────────

    public function test_generators_unavailable_without_api_key(): void
    {
        // config() returns null/default in tests, env vars not set → all unavailable
        $this->assertFalse((new OpenAIGenerator())->isAvailable());
        $this->assertFalse((new AnthropicGenerator())->isAvailable());
        $this->assertFalse((new GeminiGenerator())->isAvailable());
    }

    // ── GeneratorFactory ─────────────────────────────────────────────────────

    public function test_factory_makes_openai(): void
    {
        $gen = GeneratorFactory::make('openai');
        $this->assertInstanceOf(OpenAIGenerator::class, $gen);
    }

    public function test_factory_makes_anthropic(): void
    {
        $gen = GeneratorFactory::make('anthropic');
        $this->assertInstanceOf(AnthropicGenerator::class, $gen);
    }

    public function test_factory_makes_gemini(): void
    {
        $gen = GeneratorFactory::make('gemini');
        $this->assertInstanceOf(GeminiGenerator::class, $gen);
    }

    public function test_factory_throws_for_unknown_provider(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        GeneratorFactory::make('unknown-provider');
    }

    // ── HTTP failure handling ─────────────────────────────────────────────────

    public function test_openai_generator_throws_on_http_failure(): void
    {
        \TitanChatbotHttpStub::fake([], true); // simulate 503
        $this->expectException(\RuntimeException::class);
        (new OpenAIGenerator())->generate('hello');
    }

    public function test_anthropic_generator_throws_on_http_failure(): void
    {
        \TitanChatbotHttpStub::fake([], true); // simulate 503
        $this->expectException(\RuntimeException::class);
        (new AnthropicGenerator())->generate('hello');
    }

    public function test_gemini_generator_throws_on_http_failure(): void
    {
        \TitanChatbotHttpStub::fake([], true); // simulate 503
        $this->expectException(\RuntimeException::class);
        (new GeminiGenerator())->generate('hello');
    }

    // ── Successful response parsing ──────────────────────────────────────────

    public function test_openai_generator_returns_content(): void
    {
        \TitanChatbotHttpStub::fake([
            'choices' => [
                ['message' => ['content' => 'Hello from OpenAI!']],
            ],
            'usage' => ['prompt_tokens' => 10, 'completion_tokens' => 5, 'total_tokens' => 15],
        ]);

        $reply = (new OpenAIGenerator())->generate('hello world');
        $this->assertSame('Hello from OpenAI!', $reply);
    }

    public function test_anthropic_generator_returns_text_block(): void
    {
        \TitanChatbotHttpStub::fake([
            'content' => [
                ['type' => 'text', 'text' => 'Hello from Anthropic!'],
            ],
            'usage' => ['input_tokens' => 10, 'output_tokens' => 5],
        ]);

        $reply = (new AnthropicGenerator())->generate('hello world');
        $this->assertSame('Hello from Anthropic!', $reply);
    }

    public function test_gemini_generator_returns_text(): void
    {
        \TitanChatbotHttpStub::fake([
            'candidates' => [
                [
                    'content' => [
                        'parts' => [
                            ['text' => 'Hello from Gemini!'],
                        ],
                    ],
                ],
            ],
        ]);

        $reply = (new GeminiGenerator())->generate('hello world');
        $this->assertSame('Hello from Gemini!', $reply);
    }

    // ── System message extraction (Anthropic) ───────────────────────────────

    public function test_anthropic_strips_system_message_from_history(): void
    {
        \TitanChatbotHttpStub::fake([
            'content' => [['type' => 'text', 'text' => 'ok']],
        ]);

        // Should not throw — system message is extracted, not forwarded as a message
        $reply = (new AnthropicGenerator())->generate(
            'hello',
            [
                ['role' => 'system',    'content' => 'You are a helpful assistant.'],
                ['role' => 'assistant', 'content' => 'Hi!'],
            ]
        );
        $this->assertSame('ok', $reply);
    }

    // ── Tool/function call extraction ────────────────────────────────────────

    public function test_anthropic_extracts_tool_use_block(): void
    {
        \TitanChatbotHttpStub::fake([
            'content' => [
                ['type' => 'tool_use', 'input' => ['widget' => 'calendar']],
            ],
        ]);

        $reply = (new AnthropicGenerator())->generate('make a widget', [], [
            ['type' => 'function', 'function' => ['name' => 'render_widget', 'description' => 'Render', 'parameters' => []]],
        ]);

        $decoded = json_decode($reply, true);
        $this->assertSame('calendar', $decoded['widget'] ?? null);
    }

    public function test_gemini_extracts_function_call(): void
    {
        \TitanChatbotHttpStub::fake([
            'candidates' => [
                [
                    'content' => [
                        'parts' => [
                            ['functionCall' => ['name' => 'render_widget', 'args' => ['widget' => 'chart']]],
                        ],
                    ],
                ],
            ],
        ]);

        $reply = (new GeminiGenerator())->generate('make a widget', [], [
            ['type' => 'function', 'function' => ['name' => 'render_widget', 'description' => 'Render', 'parameters' => []]],
        ]);

        $decoded = json_decode($reply, true);
        $this->assertSame('chart', $decoded['widget'] ?? null);
    }
}
