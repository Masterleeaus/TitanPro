<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\TitanCore\AI\VectorStore\PgvectorStore;
use Modules\TitanCore\Contracts\AI\VectorStoreContract;
use Modules\TitanCore\Jobs\ReindexModuleJob;
use Modules\TitanCore\Services\EmbeddingService;
use Modules\TitanCore\Services\TitanAIRunLogService;

const VECTOR_RUNTIME_COMPANY_ID = 7;

beforeEach(function () {
    if (! Schema::hasTable('titan_module_vectors')) {
        Schema::create('titan_module_vectors', function ($table) {
            $table->id();
            $table->string('external_id')->unique();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->string('module')->nullable();
            $table->longText('content');
            $table->longText('embedding')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    if (! Schema::hasTable('ai_kb_documents')) {
        Schema::create('ai_kb_documents', function ($table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->timestamps();
        });
    }

    if (! Schema::hasTable('ai_kb_chunks')) {
        Schema::create('ai_kb_chunks', function ($table) {
            $table->id();
            $table->unsignedBigInteger('document_id');
            $table->longText('content');
            $table->timestamps();
        });
    }
});

afterEach(function () {
    Schema::dropIfExists('ai_kb_chunks');
    Schema::dropIfExists('ai_kb_documents');
    Schema::dropIfExists('titan_module_vectors');
});

test('DB vector store stores and retrieves vectors ranked by similarity and tenant scope', function () {
    $embedding = Mockery::mock(EmbeddingService::class);
    $embedding->shouldReceive('embedText')->andReturnUsing(function (string $text) {
        return match ($text) {
            'tenant-one-best' => ['vector' => [1.0, 0.0, 0.0]],
            'tenant-one-low' => ['vector' => [0.1, 0.0, 0.0]],
            'tenant-two-best' => ['vector' => [0.0, 1.0, 0.0]],
            'query' => ['vector' => [1.0, 0.0, 0.0]],
            default => ['vector' => [0.0, 0.0, 1.0]],
        };
    });

    $store = new PgvectorStore($embedding, 3);
    $store->index('doc-1', 'tenant-one-best', ['company_id' => 1, 'module' => 'TitanCore']);
    $store->index('doc-2', 'tenant-one-low', ['company_id' => 1, 'module' => 'TitanCore']);
    $store->index('doc-3', 'tenant-two-best', ['company_id' => 2, 'module' => 'TitanCore']);

    $results = $store->retrieve('query', ['company_id' => 1, 'module' => 'TitanCore'], 5);

    expect($results)->toHaveCount(2);
    expect($results[0]['source'])->toBe('doc-1');
    expect($results[0]['score'])->toBeGreaterThan($results[1]['score']);
});

test('DB vector store respects maxResults when retrieving', function () {
    $embedding = Mockery::mock(EmbeddingService::class);
    $embedding->shouldReceive('embedText')->andReturnUsing(function (string $text) {
        return match ($text) {
            'doc-a' => ['vector' => [1.0, 0.0, 0.0]],
            'doc-b' => ['vector' => [0.9, 0.0, 0.0]],
            'doc-c' => ['vector' => [0.8, 0.0, 0.0]],
            'query' => ['vector' => [1.0, 0.0, 0.0]],
            default => ['vector' => [0.0, 1.0, 0.0]],
        };
    });

    $store = new PgvectorStore($embedding, 3);
    $store->index('doc-a', 'doc-a', ['company_id' => 9, 'module' => 'TitanCore']);
    $store->index('doc-b', 'doc-b', ['company_id' => 9, 'module' => 'TitanCore']);
    $store->index('doc-c', 'doc-c', ['company_id' => 9, 'module' => 'TitanCore']);

    $results = $store->retrieve('query', ['company_id' => 9, 'module' => 'TitanCore'], 2);

    expect($results)->toHaveCount(2);
});

test('reindex job processes module documents and updates vector store', function () {
    $docId = DB::table('ai_kb_documents')->insertGetId(['company_id' => VECTOR_RUNTIME_COMPANY_ID, 'created_at' => now(), 'updated_at' => now()]);
    DB::table('ai_kb_chunks')->insert([
        ['document_id' => $docId, 'content' => 'alpha chunk', 'created_at' => now(), 'updated_at' => now()],
        ['document_id' => $docId, 'content' => 'beta chunk', 'created_at' => now(), 'updated_at' => now()],
    ]);

    $store = new class implements VectorStoreContract
    {
        public array $indexedRecords = [];
        public function index(string $id, string $content, array $metadata = []): array
        {
            $this->indexedRecords[] = compact('id', 'content', 'metadata');

            return ['ok' => true];
        }
        public function delete(string $id): array
        {
            return ['ok' => true];
        }
        public function retrieve(string $query, array $context = [], int $maxResults = 5): array
        {
            return [];
        }
    };

    $log = Mockery::mock(TitanAIRunLogService::class);
    $log->shouldReceive('create')->once()->andReturn(123);
    $log->shouldReceive('start')->once()->with(123);
    $log->shouldReceive('success')->once();

    (new ReindexModuleJob('TitanCore', companyId: VECTOR_RUNTIME_COMPANY_ID, chunkLimit: 50))->handle($store, $log);

    expect($store->indexedRecords)->toHaveCount(2);
    expect($store->indexedRecords[0]['metadata']['company_id'])->toBe(VECTOR_RUNTIME_COMPANY_ID);
    expect($store->indexedRecords[0]['metadata']['module'])->toBe('TitanCore');
});
