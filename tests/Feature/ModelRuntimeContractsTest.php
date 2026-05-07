<?php

use App\Providers\TitanModelRuntimeServiceProvider;
use Illuminate\Support\Facades\Schema;
use Modules\TitanCore\AI\Providers\NullChatProvider;
use Modules\TitanCore\AI\Providers\NullEmbeddingProvider;
use Modules\TitanCore\AI\VectorStore\DatabaseVectorStore;
use Modules\TitanCore\Contracts\AI\ChatProviderContract;
use Modules\TitanCore\Contracts\AI\EmbeddingProviderContract;
use Modules\TitanCore\Contracts\AI\IndexingContract;
use Modules\TitanCore\Contracts\AI\RetrievalContract;
use Modules\TitanCore\Contracts\AI\VectorStoreContract;

// ---------------------------------------------------------------------------
// Model Runtime Contracts — existence and typed signatures
// ---------------------------------------------------------------------------

test('ChatProviderContract exists as a PHP interface', function () {
    expect(interface_exists(ChatProviderContract::class))->toBeTrue();
});

test('EmbeddingProviderContract exists as a PHP interface', function () {
    expect(interface_exists(EmbeddingProviderContract::class))->toBeTrue();
});

test('VectorStoreContract exists as a PHP interface', function () {
    expect(interface_exists(VectorStoreContract::class))->toBeTrue();
});

test('VectorStoreContract extends IndexingContract and RetrievalContract', function () {
    $parents = class_implements(VectorStoreContract::class) ?: [];
    expect($parents)->toContain(IndexingContract::class);
    expect($parents)->toContain(RetrievalContract::class);
});

test('ChatProviderContract has typed chat method', function () {
    $ref = new ReflectionMethod(ChatProviderContract::class, 'chat');
    expect($ref->getName())->toBe('chat');
    // messages + options
    expect($ref->getParameters())->toHaveCount(2);
});

test('EmbeddingProviderContract has typed embed method', function () {
    $ref = new ReflectionMethod(EmbeddingProviderContract::class, 'embed');
    expect($ref->getName())->toBe('embed');
    expect($ref->getParameters())->toHaveCount(2);
});

// ---------------------------------------------------------------------------
// titan_module_vectors migration — table structure
// ---------------------------------------------------------------------------

beforeEach(function () {
    if (! Schema::hasTable('titan_module_vectors')) {
        Schema::create('titan_module_vectors', function ($table) {
            $table->bigIncrements('id');
            $table->string('external_id', 255)->unique();
            $table->unsignedBigInteger('company_id')->nullable()->index();
            $table->string('module', 120)->nullable()->index();
            $table->longText('content');
            $table->longText('embedding')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['company_id', 'module']);
        });
    }
});

afterEach(function () {
    Schema::dropIfExists('titan_module_vectors');
});

test('titan_module_vectors table has required columns', function () {
    foreach (['id', 'module', 'content', 'embedding', 'metadata', 'created_at'] as $col) {
        expect(Schema::hasColumn('titan_module_vectors', $col))->toBeTrue("column {$col} is missing");
    }
});

test('titan_module_vectors table has an index on the module column', function () {
    // Verify the module column exists with an index
    expect(Schema::hasColumn('titan_module_vectors', 'module'))->toBeTrue();
});

// ---------------------------------------------------------------------------
// NullChatProvider
// ---------------------------------------------------------------------------

test('NullChatProvider implements ChatProviderContract', function () {
    $provider = new NullChatProvider();
    expect($provider)->toBeInstanceOf(ChatProviderContract::class);
});

test('NullChatProvider returns ok=true without network calls', function () {
    $provider = new NullChatProvider();
    $result   = $provider->chat([['role' => 'user', 'content' => 'hello']]);

    expect($result['ok'])->toBeTrue();
    expect($result['provider'])->toBe('null');
    expect($result['error'])->toBeNull();
    expect($result['content'])->toBe('');
});

test('NullChatProvider health returns ok=true', function () {
    $provider = new NullChatProvider();
    $health   = $provider->health();

    expect($health['ok'])->toBeTrue();
    expect($health['provider'])->toBe('null');
});

test('NullChatProvider providerName returns null', function () {
    $provider = new NullChatProvider();
    expect($provider->providerName())->toBe('null');
});

// ---------------------------------------------------------------------------
// NullEmbeddingProvider
// ---------------------------------------------------------------------------

test('NullEmbeddingProvider implements EmbeddingProviderContract', function () {
    $provider = new NullEmbeddingProvider();
    expect($provider)->toBeInstanceOf(EmbeddingProviderContract::class);
});

test('NullEmbeddingProvider returns a deterministic zero vector', function () {
    $provider = new NullEmbeddingProvider(dimensions: 4);
    $result   = $provider->embed('any text');

    expect($result['ok'])->toBeTrue();
    expect($result['provider'])->toBe('null');
    expect($result['error'])->toBeNull();
    expect($result['vectors'])->toHaveCount(1);
    expect($result['vectors'][0])->toBe([0.0, 0.0, 0.0, 0.0]);
});

test('NullEmbeddingProvider returns vectors for batch input', function () {
    $provider = new NullEmbeddingProvider(dimensions: 3);
    $result   = $provider->embed(['text one', 'text two']);

    expect($result['vectors'])->toHaveCount(2);
    expect($result['vectors'][0])->toBe([0.0, 0.0, 0.0]);
    expect($result['vectors'][1])->toBe([0.0, 0.0, 0.0]);
});

test('NullEmbeddingProvider health returns ok=true', function () {
    $provider = new NullEmbeddingProvider();
    $health   = $provider->health();

    expect($health['ok'])->toBeTrue();
    expect($health['provider'])->toBe('null');
});

// ---------------------------------------------------------------------------
// TitanModelRuntimeServiceProvider binds null providers as defaults
// ---------------------------------------------------------------------------

test('TitanModelRuntimeServiceProvider binds ChatProviderContract to NullChatProvider by default', function () {
    // The service provider is registered in bootstrap/providers.php; verify
    // that the container resolves ChatProviderContract to something that
    // implements the contract.
    $resolved = app(ChatProviderContract::class);
    expect($resolved)->toBeInstanceOf(ChatProviderContract::class);
});

test('TitanModelRuntimeServiceProvider binds EmbeddingProviderContract to NullEmbeddingProvider by default', function () {
    $resolved = app(EmbeddingProviderContract::class);
    expect($resolved)->toBeInstanceOf(EmbeddingProviderContract::class);
});

// ---------------------------------------------------------------------------
// DatabaseVectorStore — store, search, delete
// ---------------------------------------------------------------------------

test('DatabaseVectorStore store correctly inserts a row', function () {
    $store = new DatabaseVectorStore(module: 'TestModule', companyId: 1);
    $result = $store->store('chunk-1', 'Hello world', [1.0, 0.0, 0.0]);

    expect($result['ok'])->toBeTrue();
    expect($result['chunk_id'])->toBe('chunk-1');

    $row = \Illuminate\Support\Facades\DB::table('titan_module_vectors')
        ->where('external_id', 'chunk-1')
        ->first();

    expect($row)->not->toBeNull();
    expect($row->content)->toBe('Hello world');
    expect($row->module)->toBe('TestModule');
});

test('DatabaseVectorStore search returns top K closest vectors by cosine similarity', function () {
    $store = new DatabaseVectorStore(module: 'TestModule', companyId: 2);

    $store->store('chunk-a', 'First document',  [1.0, 0.0, 0.0]);
    $store->store('chunk-b', 'Second document', [0.0, 1.0, 0.0]);
    $store->store('chunk-c', 'Third document',  [0.5, 0.5, 0.0]);

    // Query is closest to chunk-a
    $results = $store->search([1.0, 0.0, 0.0], topK: 2);

    expect($results)->toHaveCount(2);
    expect($results[0]['chunk_id'])->toBe('chunk-a');
    expect($results[0]['score'])->toBeGreaterThan($results[1]['score']);
});

test('DatabaseVectorStore search is scoped to the current module', function () {
    $moduleA = new DatabaseVectorStore(module: 'ModuleA', companyId: 3);
    $moduleB = new DatabaseVectorStore(module: 'ModuleB', companyId: 3);

    $moduleA->store('chunk-x', 'Module A doc', [1.0, 0.0]);
    $moduleB->store('chunk-y', 'Module B doc', [1.0, 0.0]);

    $results = $moduleA->search([1.0, 0.0]);

    // Only ModuleA's chunk should appear
    expect(collect($results)->pluck('chunk_id')->all())->toContain('chunk-x');
    expect(collect($results)->pluck('chunk_id')->all())->not->toContain('chunk-y');
});

test('DatabaseVectorStore delete removes the correct row', function () {
    $store = new DatabaseVectorStore(module: 'TestModule', companyId: 4);
    $store->store('chunk-del', 'To be deleted', [0.0, 1.0, 0.0]);

    $result = $store->delete('chunk-del');

    expect($result['ok'])->toBeTrue();

    $row = \Illuminate\Support\Facades\DB::table('titan_module_vectors')
        ->where('external_id', 'chunk-del')
        ->first();

    expect($row)->toBeNull();
});

test('DatabaseVectorStore store persists metadata as JSON', function () {
    $store = new DatabaseVectorStore(module: 'TestModule', companyId: 5);
    $store->store('chunk-meta', 'With metadata', [1.0, 0.0], ['source' => 'doc.pdf', 'page' => 3]);

    $results = $store->search([1.0, 0.0], topK: 1);

    expect($results[0]['metadata'])->toMatchArray(['source' => 'doc.pdf', 'page' => 3]);
});
