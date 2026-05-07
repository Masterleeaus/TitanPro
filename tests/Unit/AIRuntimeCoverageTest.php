<?php

use Modules\TitanCore\AI\AIOrchestratorPipeline;
use Modules\TitanCore\AI\ToolExecutor;
use Modules\TitanCore\Contracts\AI\CitationContract;
use Modules\TitanCore\Contracts\AI\GuardrailContract;
use Modules\TitanCore\Contracts\AI\RetrievalContract;

if (! class_exists('AIRuntimeEchoHandler')) {
    class AIRuntimeEchoHandler
    {
        public function __invoke(array $params): array
        {
            return ['echo' => $params];
        }
    }
}

if (! class_exists('AIRuntimeFlagHandler')) {
    class AIRuntimeFlagHandler
    {
        public static bool $called = false;

        public function __invoke(array $params): array
        {
            self::$called = true;

            return $params;
        }
    }
}

afterEach(function () {
    AIRuntimeFlagHandler::$called = false;
});

test('tool executor calls handler and writes audit record', function () {
    $audit = [];

    $executor = new ToolExecutor(
        manifest: [
            'echo' => ['handler' => AIRuntimeEchoHandler::class],
        ],
        auditWriter: function (array $entry) use (&$audit): void {
            $audit[] = $entry;
        }
    );

    $result = $executor->execute('echo', ['message' => 'hello'], ['company_id' => 7, 'user_id' => 10]);

    expect($result->ok)->toBeTrue();
    expect($result->data)->toBe(['echo' => ['message' => 'hello']]);
    expect($audit)->toHaveCount(1);
    expect($audit[0]['tool'])->toBe('echo');
    expect($audit[0]['company_id'])->toBe(7);
    expect($audit[0]['status'])->toBe('success');
});

test('guardrail blocks blocked terms before tool handler runs', function () {
    AIRuntimeFlagHandler::$called = false;

    $executor = new ToolExecutor([
        'echo' => ['handler' => AIRuntimeFlagHandler::class],
    ]);

    $guardrail = new class implements GuardrailContract
    {
        public function evaluate(array $input): array
        {
            return str_contains(strtolower((string) ($input['text'] ?? '')), 'blocked')
                ? ['pass' => false, 'reason' => 'blocked term']
                : ['pass' => true];
        }
    };

    $pipeline = new AIOrchestratorPipeline($guardrail, null, $executor, null);
    $result = $pipeline->run(['text' => 'this contains blocked text', 'tool' => 'echo', 'params' => ['a' => 1]]);

    expect($result['ok'])->toBeFalse();
    expect($result['blocked'])->toBeTrue();
    expect($result['stage'])->toBe('guardrail');
    expect(AIRuntimeFlagHandler::$called)->toBeFalse();
});

test('guardrail passing input allows tool handler execution', function () {
    AIRuntimeFlagHandler::$called = false;

    $executor = new ToolExecutor([
        'echo' => ['handler' => AIRuntimeFlagHandler::class],
    ]);

    $guardrail = new class implements GuardrailContract
    {
        public function evaluate(array $input): array
        {
            return ['pass' => true];
        }
    };

    $pipeline = new AIOrchestratorPipeline($guardrail, null, $executor, null);
    $result = $pipeline->run(['text' => 'safe input', 'tool' => 'echo', 'params' => ['x' => 1]]);

    expect($result['ok'])->toBeTrue();
    expect($result['blocked'])->toBeFalse();
    expect(AIRuntimeFlagHandler::$called)->toBeTrue();
});

test('retrieval returns chunks scoped to provided tenant', function () {
    $retrieval = new class implements RetrievalContract
    {
        public function retrieve(string $query, array $context = [], int $maxResults = 5): array
        {
            $companyId = $context['company_id'] ?? null;
            $docs = [
                ['content' => 'tenant 1 doc', 'company_id' => 1, 'source' => 'doc-1'],
                ['content' => 'tenant 2 doc', 'company_id' => 2, 'source' => 'doc-2'],
            ];

            return array_values(array_filter($docs, fn ($doc) => $doc['company_id'] === $companyId));
        }
    };

    $pipeline = new AIOrchestratorPipeline(null, $retrieval, null, null);
    $result = $pipeline->run(['text' => 'query'], ['company_id' => 2]);

    expect($result['retrieval'])->toHaveCount(1);
    expect($result['retrieval'][0]['source'])->toBe('doc-2');
});

test('citation resolver returns source references for retrieved docs', function () {
    $retrieval = new class implements RetrievalContract
    {
        public function retrieve(string $query, array $context = [], int $maxResults = 5): array
        {
            return [
                ['content' => 'chunk one', 'source' => 'kb://a'],
                ['content' => 'chunk two', 'source' => 'kb://b'],
            ];
        }
    };

    $citation = new class implements CitationContract
    {
        public function resolve(string $responseText, array $retrievedDocs): array
        {
            return array_map(
                fn (array $doc, int $i) => ['ref' => '['.($i + 1).']', 'source' => $doc['source']],
                $retrievedDocs,
                array_keys($retrievedDocs)
            );
        }
    };

    $pipeline = new AIOrchestratorPipeline(null, $retrieval, null, $citation);
    $result = $pipeline->run(['text' => 'question']);

    expect($result['citations'])->toHaveCount(2);
    expect($result['citations'][0]['source'])->toBe('kb://a');
    expect($result['citations'][1]['ref'])->toBe('[2]');
});
