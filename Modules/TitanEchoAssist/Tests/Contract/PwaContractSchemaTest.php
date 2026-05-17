<?php

namespace Modules\TitanEchoAssist\Tests\Contract;

use PHPUnit\Framework\TestCase;

/**
 * Schema-level contract tests for pwa_contract.json (Blueprint 13+14).
 *
 * Validates structure, required fields, and type constraints.
 */
class PwaContractSchemaTest extends TestCase
{
    private array $contract;

    protected function setUp(): void
    {
        $path = __DIR__ . '/../../manifests/pwa_contract.json';
        $this->assertTrue(file_exists($path), 'pwa_contract.json must exist');

        $raw = file_get_contents($path);
        $this->assertJson($raw, 'pwa_contract.json must be valid JSON');

        $this->contract = json_decode($raw, true);
    }

    public function test_top_level_schema_is_valid_string(): void
    {
        $this->assertIsString($this->contract['schema']);
        $this->assertStringStartsWith('titan.pwa.contract.', $this->contract['schema']);
    }

    public function test_module_is_string(): void
    {
        $this->assertIsString($this->contract['module']);
        $this->assertNotEmpty($this->contract['module']);
    }

    public function test_offline_cards_is_array(): void
    {
        $this->assertIsArray($this->contract['offline_cards']);
        $this->assertGreaterThan(0, count($this->contract['offline_cards']));
    }

    public function test_each_offline_card_has_string_id(): void
    {
        foreach ($this->contract['offline_cards'] as $card) {
            $this->assertIsString($card['id']);
            $this->assertNotEmpty($card['id']);
        }
    }

    public function test_each_offline_card_has_positive_ttl(): void
    {
        foreach ($this->contract['offline_cards'] as $card) {
            $this->assertIsInt($card['ttl_seconds']);
            $this->assertGreaterThan(0, $card['ttl_seconds']);
        }
    }

    public function test_each_offline_card_minimal_sync_fields_is_non_empty_array(): void
    {
        foreach ($this->contract['offline_cards'] as $card) {
            $this->assertIsArray($card['minimal_sync_fields']);
            $this->assertGreaterThan(0, count($card['minimal_sync_fields']));
        }
    }

    public function test_all_offline_cards_include_id_field_in_sync(): void
    {
        foreach ($this->contract['offline_cards'] as $card) {
            $this->assertContains('id', $card['minimal_sync_fields']);
        }
    }

    public function test_action_set_is_array(): void
    {
        $this->assertIsArray($this->contract['action_set']);
        $this->assertGreaterThan(0, count($this->contract['action_set']));
    }

    public function test_each_action_has_boolean_offline_capable(): void
    {
        foreach ($this->contract['action_set'] as $action) {
            $this->assertIsBool($action['offline_capable']);
        }
    }

    public function test_each_action_has_boolean_sync_on_reconnect(): void
    {
        foreach ($this->contract['action_set'] as $action) {
            $this->assertIsBool($action['sync_on_reconnect']);
        }
    }

    public function test_push_triggers_is_non_empty_array(): void
    {
        $this->assertIsArray($this->contract['push_triggers']);
        $this->assertGreaterThan(0, count($this->contract['push_triggers']));
    }

    public function test_each_push_trigger_event_is_namespaced(): void
    {
        foreach ($this->contract['push_triggers'] as $trigger) {
            $this->assertStringContainsString('.', $trigger['event'], 'Push trigger events must be namespaced');
        }
    }

    public function test_service_worker_cache_strategy_is_string(): void
    {
        $this->assertIsString($this->contract['service_worker']['cache_strategy']);
        $this->assertContains(
            $this->contract['service_worker']['cache_strategy'],
            ['cache-first', 'network-first', 'stale-while-revalidate', 'network-only', 'cache-only']
        );
    }

    public function test_installable_surface_is_boolean(): void
    {
        $this->assertIsBool($this->contract['installable_surface']);
    }

    public function test_indexed_db_stores_all_have_key_path(): void
    {
        foreach ($this->contract['indexed_db']['stores'] as $store) {
            $this->assertArrayHasKey('key_path', $store);
            $this->assertIsString($store['key_path']);
        }
    }

    public function test_indexed_db_stores_all_have_indexes_array(): void
    {
        foreach ($this->contract['indexed_db']['stores'] as $store) {
            $this->assertArrayHasKey('indexes', $store);
            $this->assertIsArray($store['indexes']);
        }
    }
}
