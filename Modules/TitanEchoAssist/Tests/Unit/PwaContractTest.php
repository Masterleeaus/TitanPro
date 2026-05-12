<?php

namespace Modules\TitanEchoAssist\Tests\Unit;

use PHPUnit\Framework\TestCase;

/**
 * Contract test: validates that pwa_contract.json exists and contains the
 * required fields per Blueprint 13 and 14.
 */
class PwaContractTest extends TestCase
{
    private array $contract;
    private string $contractPath;

    protected function setUp(): void
    {
        $this->contractPath = __DIR__ . '/../../manifests/pwa_contract.json';
        $this->assertTrue(
            file_exists($this->contractPath),
            'pwa_contract.json must exist at Modules/TitanEchoAssist/manifests/pwa_contract.json'
        );

        $content = file_get_contents($this->contractPath);
        $this->contract = json_decode($content, true);
        $this->assertNotNull($this->contract, 'pwa_contract.json must be valid JSON');
    }

    public function test_schema_field_is_correct(): void
    {
        $this->assertSame('titan.pwa.contract.v1', $this->contract['schema']);
    }

    public function test_module_field_matches(): void
    {
        $this->assertSame('TitanEchoAssist', $this->contract['module']);
    }

    public function test_offline_cards_are_present(): void
    {
        $this->assertArrayHasKey('offline_cards', $this->contract);
        $this->assertNotEmpty($this->contract['offline_cards']);
    }

    public function test_offline_cards_contain_company_id_in_sync_fields(): void
    {
        foreach ($this->contract['offline_cards'] as $card) {
            $this->assertArrayHasKey('minimal_sync_fields', $card);
            $this->assertContains(
                'company_id',
                $card['minimal_sync_fields'],
                "offline card '{$card['id']}' must include company_id in minimal_sync_fields"
            );
        }
    }

    public function test_offline_cards_have_required_keys(): void
    {
        foreach ($this->contract['offline_cards'] as $card) {
            $this->assertArrayHasKey('id', $card);
            $this->assertArrayHasKey('label', $card);
            $this->assertArrayHasKey('minimal_sync_fields', $card);
            $this->assertArrayHasKey('ttl_seconds', $card);
        }
    }

    public function test_push_triggers_are_present(): void
    {
        $this->assertArrayHasKey('push_triggers', $this->contract);
        $this->assertNotEmpty($this->contract['push_triggers']);
    }

    public function test_push_triggers_have_required_keys(): void
    {
        foreach ($this->contract['push_triggers'] as $trigger) {
            $this->assertArrayHasKey('event', $trigger);
            $this->assertArrayHasKey('notification_id', $trigger);
            $this->assertArrayHasKey('label', $trigger);
        }
    }

    public function test_push_triggers_include_message_received(): void
    {
        $events = array_column($this->contract['push_triggers'], 'event');
        $this->assertContains('titanechoassist.message.received', $events);
    }

    public function test_push_triggers_include_conversation_started(): void
    {
        $events = array_column($this->contract['push_triggers'], 'event');
        $this->assertContains('titanechoassist.conversation.started', $events);
    }

    public function test_action_set_includes_offline_capable_send(): void
    {
        $this->assertArrayHasKey('action_set', $this->contract);

        $offlineSend = null;
        foreach ($this->contract['action_set'] as $action) {
            if ($action['id'] === 'send_message_offline') {
                $offlineSend = $action;
                break;
            }
        }

        $this->assertNotNull($offlineSend, 'action_set must include send_message_offline');
        $this->assertTrue($offlineSend['offline_capable']);
        $this->assertTrue($offlineSend['sync_on_reconnect']);
    }

    public function test_action_set_references_sync_job(): void
    {
        foreach ($this->contract['action_set'] as $action) {
            if (isset($action['job_class'])) {
                $this->assertStringContainsString('SyncOfflineConversationJob', $action['job_class']);
            }
        }
    }

    public function test_indexed_db_schema_is_defined(): void
    {
        $this->assertArrayHasKey('indexed_db', $this->contract);
        $this->assertArrayHasKey('stores', $this->contract['indexed_db']);
        $this->assertNotEmpty($this->contract['indexed_db']['stores']);
    }

    public function test_indexed_db_has_offline_queue_store(): void
    {
        $stores     = $this->contract['indexed_db']['stores'];
        $storeNames = array_column($stores, 'name');
        $this->assertContains('offline_queue', $storeNames);
    }

    public function test_indexed_db_offline_queue_indexed_by_company_id(): void
    {
        $stores = $this->contract['indexed_db']['stores'];
        foreach ($stores as $store) {
            if ($store['name'] === 'offline_queue') {
                $indexNames = array_column($store['indexes'], 'name');
                $this->assertContains('company_id', $indexNames);
                return;
            }
        }
        $this->fail('offline_queue store not found');
    }

    public function test_service_worker_config_is_present(): void
    {
        $this->assertArrayHasKey('service_worker', $this->contract);
        $sw = $this->contract['service_worker'];
        $this->assertArrayHasKey('cache_strategy', $sw);
        $this->assertArrayHasKey('offline_fallback', $sw);
    }
}
