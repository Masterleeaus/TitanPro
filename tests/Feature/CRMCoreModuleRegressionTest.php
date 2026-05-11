<?php

use Modules\CRMCore\Interfaces\PipelineMetricProvider;
use Modules\CRMCore\Providers\RepositoryServiceProvider;
use Modules\CRMCore\Repositories\PipelineRepository;

test('crmcore repository provider binds pipeline metric contract', function () {
    (new RepositoryServiceProvider(app()))->register();

    $resolved = app(PipelineMetricProvider::class);

    expect($resolved)->toBeInstanceOf(PipelineRepository::class);
});

test('crmcore pipeline repository scopes queries to request tenant header', function () {
    request()->headers->set('X-Tenant-Id', 'tenant-regression');

    $repository = new PipelineRepository();

    $leadQuery = $repository->leads();
    $dealQuery = $repository->deals();

    expect($leadQuery->toSql())->toContain('tenant_id');
    expect($dealQuery->toSql())->toContain('tenant_id');
    expect($leadQuery->getBindings())->toContain('tenant-regression');
    expect($dealQuery->getBindings())->toContain('tenant-regression');
});

test('crmcore ai and workflow manifests use canonical schema contracts', function () {
    $aiManifestPath = base_path('Modules/CRMCore/manifests/ai.manifest.json');
    $workflowManifestPath = base_path('Modules/CRMCore/manifests/workflows.manifest.json');

    $ai = json_decode((string) file_get_contents($aiManifestPath), true);
    $workflow = json_decode((string) file_get_contents($workflowManifestPath), true);

    expect($ai)->toBeArray()
        ->and($ai['schema_version'] ?? null)->toBe('1.0.0')
        ->and($ai['module'] ?? null)->toBe('CRMCore');

    expect($workflow)->toBeArray()
        ->and($workflow['schema_version'] ?? null)->toBe('1.0.0')
        ->and($workflow['module'] ?? null)->toBe('CRMCore')
        ->and($workflow['workflows'] ?? [])->toContain('Modules\\CRMCore\\Workflows\\Definitions\\DealToProjectWorkflow')
        ->and($workflow['triggers'] ?? [])->toContain('crmcore.deal.won');
});

test('crmcore signal listener and event contracts stay canonical', function () {
    $eventsPath = base_path('Modules/CRMCore/manifests/events.manifest.json');
    $listenersPath = base_path('Modules/CRMCore/manifests/listeners.manifest.json');

    $events = json_decode((string) file_get_contents($eventsPath), true);
    $listeners = json_decode((string) file_get_contents($listenersPath), true);

    expect($events['module'] ?? null)->toBe('CRMCore')
        ->and($events['emits'] ?? [])->toContain('crmcore.lead.scored', 'crmcore.deal.converted_to_project');

    expect($listeners['module'] ?? null)->toBe('CRMCore')
        ->and($listeners['listeners'] ?? [])->toContain(
            'Modules\\CRMCore\\Listeners\\RecordLeadScored',
            'Modules\\CRMCore\\Listeners\\RecordDealConvertedToProject',
        );
});
