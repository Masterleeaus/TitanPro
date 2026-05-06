<?php

namespace Modules\TitanCore\Tests\Unit;

use Modules\TitanCore\AI\AIOrchestratorPipeline;
use Modules\TitanCore\AI\ToolExecutor;
use Modules\TitanCore\AI\ValueObjects\ToolResult;
use Modules\TitanCore\Contracts\AI\CitationContract;
use Modules\TitanCore\Contracts\AI\GuardrailContract;
use Modules\TitanCore\Contracts\AI\RetrievalContract;
use Modules\TitanCore\Contracts\AI\ToolExecutorContract;
use Modules\TitanCore\Exceptions\AI\ToolHandlerNotFoundException;
use Modules\TitanCore\Exceptions\AI\ToolInputValidationException;
use PHPUnit\Framework\TestCase;

// ─── Inline handler stubs ─────────────────────────────────────────────────────

/** @internal Minimal valid handler used in test scenarios. */
class EchoToolHandler
{
    public function __invoke(array $params): array
    {
        return ['echo' => $params];
    }
}

// ─────────────────────────────────────────────────────────────────────────────

class ToolExecutorTest extends TestCase
{
    // ── Contracts interface smoke tests ──────────────────────────────────────

    public function test_tool_executor_contract_is_interface(): void
    {
        $this->assertTrue(interface_exists(ToolExecutorContract::class));
    }

    public function test_retrieval_contract_is_interface(): void
    {
        $this->assertTrue(interface_exists(RetrievalContract::class));
    }

    public function test_indexing_contract_is_interface(): void
    {
        $this->assertTrue(interface_exists(\Modules\TitanCore\Contracts\AI\IndexingContract::class));
    }

    public function test_guardrail_contract_is_interface(): void
    {
        $this->assertTrue(interface_exists(GuardrailContract::class));
    }

    public function test_citation_contract_is_interface(): void
    {
        $this->assertTrue(interface_exists(CitationContract::class));
    }

    // ── ToolResult value object ───────────────────────────────────────────────

    public function test_tool_result_to_array_contains_required_keys(): void
    {
        $result = new ToolResult(
            ok: true,
            tool: 'test.tool',
            data: ['key' => 'value'],
            message: 'ok',
        );

        $arr = $result->toArray();

        $this->assertArrayHasKey('ok', $arr);
        $this->assertArrayHasKey('tool', $arr);
        $this->assertArrayHasKey('data', $arr);
        $this->assertArrayHasKey('message', $arr);
        $this->assertArrayHasKey('warnings', $arr);
        $this->assertArrayHasKey('audit_ref', $arr);
        $this->assertTrue($arr['ok']);
        $this->assertSame('test.tool', $arr['tool']);
    }

    // ── ToolExecutor: successful tool call ────────────────────────────────────

    public function test_successful_tool_call_returns_tool_result(): void
    {
        $executor = new ToolExecutor([
            'echo' => [
                'handler' => EchoToolHandler::class,
                'input_schema' => ['message' => 'required|string'],
            ],
        ]);

        $result = $executor->execute('echo', ['message' => 'hello']);

        $this->assertInstanceOf(ToolResult::class, $result);
        $this->assertTrue($result->ok);
        $this->assertSame('echo', $result->tool);
        $this->assertSame(['echo' => ['message' => 'hello']], $result->data);
    }

    // ── ToolExecutor: missing handler ─────────────────────────────────────────

    public function test_missing_handler_class_throws_tool_handler_not_found_exception(): void
    {
        $executor = new ToolExecutor([
            'ghost.tool' => [
                'handler' => 'Modules\TitanCore\Tools\NonExistentHandler',
            ],
        ]);

        $this->expectException(ToolHandlerNotFoundException::class);
        $this->expectExceptionMessageMatches('/ghost\.tool/');
        $this->expectExceptionMessageMatches('/NonExistentHandler/');

        $executor->execute('ghost.tool', []);
    }

    public function test_tool_not_in_manifest_throws_tool_handler_not_found_exception(): void
    {
        $executor = new ToolExecutor([]);

        $this->expectException(ToolHandlerNotFoundException::class);
        $executor->execute('unknown.tool', []);
    }

    // ── ToolExecutor: input validation failure ────────────────────────────────

    public function test_missing_required_field_throws_input_validation_exception(): void
    {
        $executor = new ToolExecutor([
            'echo' => [
                'handler'      => EchoToolHandler::class,
                'input_schema' => ['message' => 'required|string'],
            ],
        ]);

        $this->expectException(ToolInputValidationException::class);
        $this->expectExceptionMessageMatches('/message/');

        $executor->execute('echo', []); // 'message' param missing
    }

    public function test_empty_required_field_throws_input_validation_exception(): void
    {
        $executor = new ToolExecutor([
            'echo' => [
                'handler'      => EchoToolHandler::class,
                'input_schema' => ['message' => 'required'],
            ],
        ]);

        $this->expectException(ToolInputValidationException::class);

        $executor->execute('echo', ['message' => '']);
    }

    public function test_validation_exception_carries_field_errors(): void
    {
        $executor = new ToolExecutor([
            'echo' => [
                'handler'      => EchoToolHandler::class,
                'input_schema' => ['name' => 'required', 'value' => 'required'],
            ],
        ]);

        try {
            $executor->execute('echo', []);
            $this->fail('Expected ToolInputValidationException was not thrown.');
        } catch (ToolInputValidationException $e) {
            $this->assertArrayHasKey('name', $e->errors);
            $this->assertArrayHasKey('value', $e->errors);
        }
    }

    // ── AIOrchestratorPipeline ────────────────────────────────────────────────

    public function test_pipeline_runs_to_completion_with_all_null_stages(): void
    {
        $pipeline = new AIOrchestratorPipeline(null, null, null, null);

        $result = $pipeline->run(['text' => 'hello']);

        $this->assertTrue($result['ok']);
        $this->assertFalse($result['blocked']);
        $this->assertSame('complete', $result['stage']);
        $this->assertNull($result['tool_result']);
    }

    public function test_pipeline_guardrail_hit_short_circuits(): void
    {
        $guardrail = new class implements GuardrailContract {
            public function evaluate(array $input): array
            {
                return ['pass' => false, 'reason' => 'blocked content'];
            }
        };

        $pipeline = new AIOrchestratorPipeline($guardrail, null, null, null);

        $result = $pipeline->run(['text' => 'bad input']);

        $this->assertFalse($result['ok']);
        $this->assertTrue($result['blocked']);
        $this->assertSame('guardrail', $result['stage']);
        $this->assertSame('blocked content', $result['reason']);
    }

    public function test_pipeline_invokes_retrieval_and_returns_docs(): void
    {
        $retrieval = new class implements RetrievalContract {
            public function retrieve(string $query, array $context = [], int $maxResults = 5): array
            {
                return [['content' => 'doc1', 'score' => 0.9, 'source' => 'kb']];
            }
        };

        $pipeline = new AIOrchestratorPipeline(null, $retrieval, null, null);

        $result = $pipeline->run(['text' => 'query']);

        $this->assertTrue($result['ok']);
        $this->assertCount(1, $result['retrieval']);
        $this->assertSame('doc1', $result['retrieval'][0]['content']);
    }

    public function test_pipeline_executes_tool_and_returns_result(): void
    {
        $executor = new ToolExecutor([
            'echo' => ['handler' => EchoToolHandler::class],
        ]);

        $pipeline = new AIOrchestratorPipeline(null, null, $executor, null);

        $result = $pipeline->run(['text' => 'run tool', 'tool' => 'echo', 'params' => ['x' => 1]]);

        $this->assertTrue($result['ok']);
        $this->assertNotNull($result['tool_result']);
        $this->assertTrue($result['tool_result']['ok']);
        $this->assertSame('echo', $result['tool_result']['tool']);
    }

    public function test_pipeline_resolves_citations_from_retrieved_docs(): void
    {
        $retrieval = new class implements RetrievalContract {
            public function retrieve(string $query, array $context = [], int $maxResults = 5): array
            {
                return [['content' => 'snippet', 'score' => 0.8, 'source' => 'doc.pdf']];
            }
        };

        $citation = new class implements CitationContract {
            public function resolve(string $responseText, array $retrievedDocs): array
            {
                return array_map(fn($d) => ['ref' => '[1]', 'source' => $d['source'], 'excerpt' => $d['content']], $retrievedDocs);
            }
        };

        $pipeline = new AIOrchestratorPipeline(null, $retrieval, null, $citation);

        $result = $pipeline->run(['text' => 'query about something']);

        $this->assertCount(1, $result['citations']);
        $this->assertSame('doc.pdf', $result['citations'][0]['source']);
    }

    public function test_pipeline_passing_guardrail_continues_to_completion(): void
    {
        $guardrail = new class implements GuardrailContract {
            public function evaluate(array $input): array
            {
                return ['pass' => true];
            }
        };

        $pipeline = new AIOrchestratorPipeline($guardrail, null, null, null);

        $result = $pipeline->run(['text' => 'safe input']);

        $this->assertTrue($result['ok']);
        $this->assertFalse($result['blocked']);
        $this->assertSame('complete', $result['stage']);
    }
}
