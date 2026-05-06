<?php

namespace Modules\TitanZero\Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\TitanZero\DTO\RetrievalResult;
use Modules\TitanZero\Entities\TitanZeroDocument;
use Modules\TitanZero\Entities\TitanZeroDocumentChunk;
use Modules\TitanZero\Services\Retrieval\ManifestDrivenRetrievalService;

/**
 * Verifies the manifest-driven retrieval runtime.
 *
 * Uses RefreshDatabase so each test starts with a clean in-memory SQLite DB.
 */
class ManifestDrivenRetrievalTest extends TestCase
{
    use RefreshDatabase;

    private ManifestDrivenRetrievalService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new ManifestDrivenRetrievalService();
    }

    // -------------------------------------------------------------------------
    // Empty-index safety
    // -------------------------------------------------------------------------

    public function test_empty_index_returns_empty_collection_no_exception(): void
    {
        $results = $this->service->retrieve(
            policy:     ['top_k' => 5],
            query:      'cleaning schedule',
            company_id: 1,
        );

        $this->assertIsArray($results);
        $this->assertEmpty($results);
    }

    public function test_blank_query_returns_empty_collection(): void
    {
        $this->seedChunks(1, 'Service agreement for weekly cleaning.');

        $results = $this->service->retrieve(
            policy:     ['top_k' => 5],
            query:      '   ',
            company_id: 1,
        );

        $this->assertEmpty($results);
    }

    // -------------------------------------------------------------------------
    // Return type contract
    // -------------------------------------------------------------------------

    public function test_results_are_retrieval_result_instances(): void
    {
        $this->seedChunks(1, 'The cleaning schedule covers all common areas.');

        $results = $this->service->retrieve(
            policy:     ['top_k' => 5],
            query:      'cleaning schedule',
            company_id: 1,
        );

        $this->assertNotEmpty($results);
        foreach ($results as $result) {
            $this->assertInstanceOf(RetrievalResult::class, $result);
        }
    }

    public function test_retrieval_result_has_required_fields(): void
    {
        $this->seedChunks(1, 'Weekly cleaning checklist for common areas.');

        $results = $this->service->retrieve(
            policy:     ['top_k' => 5],
            query:      'cleaning checklist',
            company_id: 1,
        );

        $this->assertNotEmpty($results);
        $result = $results[0];

        $this->assertNotEmpty($result->chunk_id);
        $this->assertGreaterThan(0, $result->document_id);
        $this->assertGreaterThanOrEqual(0, $result->chunk_index);
        $this->assertNotEmpty($result->text);
        $this->assertIsArray($result->metadata);
        $this->assertGreaterThan(0, $result->score);
        // chunk_id is "{document_id}:{chunk_index}"
        $this->assertStringContainsString(':', $result->chunk_id);
    }

    // -------------------------------------------------------------------------
    // Manifest policy — top_k
    // -------------------------------------------------------------------------

    public function test_top_k_from_policy_limits_results(): void
    {
        // Seed 10 chunks that all match the query.
        for ($i = 0; $i < 10; $i++) {
            $this->seedChunks(1, "Cleaning procedure step {$i}: sanitise all surfaces.");
        }

        $results = $this->service->retrieve(
            policy:     ['top_k' => 3],
            query:      'cleaning procedure',
            company_id: 1,
        );

        $this->assertLessThanOrEqual(3, count($results));
    }

    public function test_limit_parameter_overrides_policy_top_k(): void
    {
        for ($i = 0; $i < 10; $i++) {
            $this->seedChunks(1, "Cleaning procedure step {$i}: sanitise all surfaces.");
        }

        $results = $this->service->retrieve(
            policy:     ['top_k' => 10],
            query:      'cleaning procedure',
            company_id: 1,
            limit:      2,
        );

        $this->assertLessThanOrEqual(2, count($results));
    }

    // -------------------------------------------------------------------------
    // Tenant scoping — no cross-tenant leakage
    // -------------------------------------------------------------------------

    public function test_retrieval_scoped_to_tenant_does_not_return_other_tenant_chunks(): void
    {
        // Seed chunks for tenant 1 and tenant 2.
        $this->seedChunks(1, 'Tenant one cleaning schedule weekly.');
        $this->seedChunks(2, 'Tenant two cleaning schedule daily.');

        $results = $this->service->retrieve(
            policy:     ['top_k' => 10],
            query:      'cleaning schedule',
            company_id: 1,
        );

        foreach ($results as $result) {
            $this->assertSame(1, $result->metadata['company_id'],
                'Results must belong to tenant 1 only');
        }
    }

    public function test_retrieval_with_null_company_id_returns_all_tenants(): void
    {
        $this->seedChunks(1, 'Tenant one cleaning schedule weekly.');
        $this->seedChunks(2, 'Tenant two cleaning schedule daily.');

        $results = $this->service->retrieve(
            policy:     ['top_k' => 10],
            query:      'cleaning schedule',
            company_id: null,
        );

        $tenants = array_unique(array_column(array_column($results, 'metadata'), 'company_id'));
        $this->assertCount(2, $tenants, 'Platform-level search should include all tenants');
    }

    // -------------------------------------------------------------------------
    // loadPolicy helper
    // -------------------------------------------------------------------------

    public function test_load_policy_returns_array_from_valid_json_file(): void
    {
        $path = module_path('TitanZero', 'AI/Retrieval/retrieval.policy.json');

        $policy = ManifestDrivenRetrievalService::loadPolicy($path);

        $this->assertIsArray($policy);
        $this->assertArrayHasKey('schema', $policy);
        $this->assertSame('titan.retrieval_policy.v1', $policy['schema']);
        $this->assertArrayHasKey('top_k', $policy);
    }

    public function test_load_policy_returns_empty_array_for_missing_file(): void
    {
        $policy = ManifestDrivenRetrievalService::loadPolicy('/tmp/non_existent_policy.json');

        $this->assertIsArray($policy);
        $this->assertEmpty($policy);
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    private function seedChunks(int $companyId, string $content, int $count = 1): void
    {
        $doc = TitanZeroDocument::create([
            'company_id' => $companyId,
            'title'      => "Test document for company {$companyId}",
            'source'     => 'test',
        ]);

        for ($i = 0; $i < $count; $i++) {
            TitanZeroDocumentChunk::create([
                'document_id'  => $doc->id,
                'chunk_index'  => $i,
                'content'      => $content,
                'content_hash' => sha1($content . $i),
            ]);
        }
    }
}
