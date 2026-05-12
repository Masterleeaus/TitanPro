<?php

namespace Tests\Unit;

use Modules\CRMCore\Interfaces\PipelineMetricProvider;
use Modules\CRMCore\Providers\RepositoryServiceProvider;
use Modules\CRMCore\Repositories\PipelineRepository;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CRMCoreModuleRegressionTest extends TestCase
{
    #[Test]
    public function crmcore_repository_provider_binds_pipeline_metric_contract(): void
    {
        (new RepositoryServiceProvider($this->app))->register();

        $resolved = $this->app->make(PipelineMetricProvider::class);

        $this->assertInstanceOf(PipelineRepository::class, $resolved);
    }

    #[Test]
    public function crmcore_pipeline_repository_scopes_queries_to_request_tenant_header(): void
    {
        request()->headers->set('X-Tenant-Id', 'tenant-regression');

        $repository = new PipelineRepository;

        $leadQuery = $repository->leads();
        $dealQuery = $repository->deals();

        $this->assertStringContainsString('tenant_id', $leadQuery->toSql());
        $this->assertStringContainsString('tenant_id', $dealQuery->toSql());
        $this->assertContains('tenant-regression', $leadQuery->getBindings());
        $this->assertContains('tenant-regression', $dealQuery->getBindings());
    }

    #[Test]
    public function crmcore_ai_and_workflow_manifests_use_canonical_schema_contracts(): void
    {
        $aiManifestPath = base_path('Modules/CRMCore/manifests/ai.manifest.json');
        $workflowManifestPath = base_path('Modules/CRMCore/manifests/workflows.manifest.json');

        $ai = json_decode((string) file_get_contents($aiManifestPath), true);
        $workflow = json_decode((string) file_get_contents($workflowManifestPath), true);

        $this->assertIsArray($ai);
        $this->assertSame('1.0.0', $ai['schema_version'] ?? null);
        $this->assertSame('CRMCore', $ai['module'] ?? null);

        $this->assertIsArray($workflow);
        $this->assertSame('1.0.0', $workflow['schema_version'] ?? null);
        $this->assertSame('CRMCore', $workflow['module'] ?? null);
        $this->assertContains('Modules\\CRMCore\\Workflows\\Definitions\\DealToProjectWorkflow', $workflow['workflows'] ?? []);
        $this->assertContains('crmcore.deal.won', $workflow['triggers'] ?? []);
    }

    #[Test]
    public function crmcore_signal_listener_and_event_contracts_stay_canonical(): void
    {
        $eventsPath = base_path('Modules/CRMCore/manifests/events.manifest.json');
        $listenersPath = base_path('Modules/CRMCore/manifests/listeners.manifest.json');

        $events = json_decode((string) file_get_contents($eventsPath), true);
        $listeners = json_decode((string) file_get_contents($listenersPath), true);

        $this->assertSame('CRMCore', $events['module'] ?? null);
        $this->assertContains('crmcore.lead.scored', $events['emits'] ?? []);
        $this->assertContains('crmcore.deal.converted_to_project', $events['emits'] ?? []);

        $this->assertSame('CRMCore', $listeners['module'] ?? null);
        $this->assertContains('Modules\\CRMCore\\Listeners\\RecordLeadScored', $listeners['listeners'] ?? []);
        $this->assertContains('Modules\\CRMCore\\Listeners\\RecordDealConvertedToProject', $listeners['listeners'] ?? []);
    }
}
