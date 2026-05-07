<?php

use App\Platform\Automation\AutomationRegistry;
use App\Platform\Billing\BillingRegistry;
use App\Platform\Filament\FilamentRegistry;
use App\Platform\Modules\ModuleManifestRegistryLoader;
use App\Platform\Search\SearchRegistry;
use App\Platform\Tenancy\TenancyRegistry;
use App\Platform\Verticals\VerticalPackRegistry;
use App\Platform\Verticals\VerticalResolver;
use App\Platform\Workflows\WorkflowDefinitionRegistry;

function writeJson(string $path, array $data): void
{
    if (! is_dir(dirname($path))) {
        mkdir(dirname($path), 0755, true);
    }

    file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
}

function deleteDirectory(string $dir): void
{
    if (! is_dir($dir)) {
        return;
    }

    foreach (scandir($dir) ?: [] as $item) {
        if ($item === '.' || $item === '..') {
            continue;
        }

        $path = $dir.'/'.$item;
        if (is_dir($path)) {
            deleteDirectory($path);
        } else {
            unlink($path);
        }
    }

    rmdir($dir);
}

function writePhpConfig(string $path, array $data): void
{
    if (! is_dir(dirname($path))) {
        mkdir(dirname($path), 0755, true);
    }

    file_put_contents($path, "<?php\n\nreturn ".var_export($data, true).";\n");
}

test('manifest loader populates registries and excludes disabled modules idempotently', function () {
    $base = sys_get_temp_dir().'/titan_registry_loader_'.uniqid('', true);
    $modulesPath = $base.'/Modules';

    mkdir($modulesPath, 0755, true);

    // Enabled module with explicit manifest declarations.
    writeJson($modulesPath.'/RegistryTestModule/module.json', [
        'name' => 'RegistryTestModule',
        'active' => 1,
    ]);
    writeJson($modulesPath.'/RegistryTestModule/manifests/ui.manifest.json', [
        'resources' => ['Modules\\RegistryTestModule\\Filament\\Resources\\OrderResource'],
        'pages' => ['Modules\\RegistryTestModule\\Filament\\Pages\\RegistryPage'],
        'widgets' => ['Modules\\RegistryTestModule\\Filament\\Widgets\\RegistryWidget'],
    ]);
    writeJson($modulesPath.'/RegistryTestModule/manifests/workflows.manifest.json', [
        'workflows' => [
            'Modules\\RegistryTestModule\\Workflows\\Definitions\\OrderWorkflow',
            ['key' => 'registry.secondary', 'class' => 'Modules\\RegistryTestModule\\Workflows\\Definitions\\SecondaryWorkflow'],
        ],
    ]);
    writeJson($modulesPath.'/RegistryTestModule/manifests/automation.manifest.json', [
        'triggers' => ['RegistryTrigger'],
        'handlers' => ['RegistryHandler'],
        'pipelines' => ['RegistryPipeline'],
        'schedulers' => [
            ['key' => 'registry.daily', 'class' => 'Modules\\RegistryTestModule\\Automation\\Schedulers\\RegistryDailyScheduler', 'schedule' => 'daily'],
        ],
    ]);
    writeJson($modulesPath.'/RegistryTestModule/manifests/billing.manifest.json', [
        'plans' => [
            ['key' => 'registry.plan.pro', 'name' => 'Registry Pro'],
        ],
        'meters' => [
            ['key' => 'registry.usage', 'class' => 'Modules\\RegistryTestModule\\Billing\\Meters\\RegistryUsageMeter'],
        ],
        'limits' => [
            ['key' => 'registry.projects', 'value' => 10],
        ],
    ]);
    writeJson($modulesPath.'/RegistryTestModule/manifests/search.manifest.json', [
        'indexes' => [
            [
                'key' => 'registry.orders',
                'model' => 'Modules\\RegistryTestModule\\Models\\Order',
                'fields' => ['reference', 'customer_name'],
            ],
        ],
    ]);
    writeJson($modulesPath.'/RegistryTestModule/manifests/tenancy.manifest.json', [
        'resolvers' => [
            ['key' => 'registry.tenant', 'class' => 'Modules\\RegistryTestModule\\Tenancy\\Resolvers\\RegistryTenantResolver'],
        ],
        'policies' => [
            ['key' => 'registry.access', 'class' => 'Modules\\RegistryTestModule\\Tenancy\\Policies\\RegistryTenantPolicy'],
        ],
    ]);
    writeJson($modulesPath.'/RegistryTestModule/manifests/verticals.json', [
        'source' => 'Config/verticals.php',
        'default' => 'field-service',
    ]);
    writePhpConfig($modulesPath.'/RegistryTestModule/Config/verticals.php', [
        'default' => 'field-service',
        'supported' => [
            'field-service' => ['label' => 'Field Service'],
            'cleaning' => ['label' => 'Cleaning'],
        ],
    ]);

    // Disabled module should not be loaded into any registry.
    writeJson($modulesPath.'/DisabledModule/module.json', [
        'name' => 'DisabledModule',
        'active' => 0,
    ]);
    writeJson($modulesPath.'/DisabledModule/manifests/ui.manifest.json', [
        'resources' => ['Modules\\DisabledModule\\Filament\\Resources\\DisabledResource'],
        'pages' => ['Modules\\DisabledModule\\Filament\\Pages\\DisabledPage'],
        'widgets' => ['Modules\\DisabledModule\\Filament\\Widgets\\DisabledWidget'],
    ]);
    writeJson($modulesPath.'/DisabledModule/manifests/workflows.manifest.json', [
        'workflows' => ['Modules\\DisabledModule\\Workflows\\Definitions\\DisabledWorkflow'],
    ]);
    writeJson($modulesPath.'/DisabledModule/manifests/automation.manifest.json', [
        'handlers' => ['DisabledHandler'],
    ]);
    writeJson($modulesPath.'/DisabledModule/manifests/billing.manifest.json', [
        'plans' => [['key' => 'disabled.plan']],
        'meters' => [['key' => 'disabled.meter']],
        'limits' => [['key' => 'disabled.limit']],
    ]);
    writeJson($modulesPath.'/DisabledModule/manifests/search.manifest.json', [
        'indexes' => [['key' => 'disabled.index']],
    ]);
    writeJson($modulesPath.'/DisabledModule/manifests/tenancy.manifest.json', [
        'resolvers' => [['key' => 'disabled.resolver']],
        'policies' => [['key' => 'disabled.policy']],
    ]);
    writeJson($modulesPath.'/DisabledModule/manifests/verticals.json', [
        'default' => 'disabled',
        'verticals' => [
            'disabled' => ['label' => 'Disabled'],
        ],
    ]);

    $filamentRegistry = new FilamentRegistry;
    $workflowRegistry = new WorkflowDefinitionRegistry;
    $automationRegistry = new AutomationRegistry;
    $verticalPackRegistry = new VerticalPackRegistry;
    $billingRegistry = new BillingRegistry;
    $searchRegistry = new SearchRegistry;
    $tenancyRegistry = new TenancyRegistry;
    $loader = new ModuleManifestRegistryLoader(
        $filamentRegistry,
        $workflowRegistry,
        $automationRegistry,
        $verticalPackRegistry,
        $billingRegistry,
        $searchRegistry,
        $tenancyRegistry,
    );

    // Load twice to verify idempotency.
    $loader->load($modulesPath);
    $loader->load($modulesPath);

    expect($filamentRegistry->resources('RegistryTestModule'))->toBe([
        'Modules\\RegistryTestModule\\Filament\\Resources\\OrderResource',
    ]);
    expect($filamentRegistry->pages('RegistryTestModule'))->toBe([
        'Modules\\RegistryTestModule\\Filament\\Pages\\RegistryPage',
    ]);
    expect($filamentRegistry->widgets('RegistryTestModule'))->toBe([
        'Modules\\RegistryTestModule\\Filament\\Widgets\\RegistryWidget',
    ]);

    expect($workflowRegistry->byModule('RegistryTestModule'))->toHaveCount(2);
    expect($workflowRegistry->find('registry.secondary'))->not->toBeNull();
    expect($workflowRegistry->find('registry.secondary')['class'])->toBe('Modules\\RegistryTestModule\\Workflows\\Definitions\\SecondaryWorkflow');

    expect($automationRegistry->triggers('RegistryTestModule'))->toHaveCount(1);
    expect($automationRegistry->handlers('RegistryTestModule'))->toHaveCount(1);
    expect($automationRegistry->pipelines('RegistryTestModule'))->toHaveCount(1);
    expect($automationRegistry->schedulerHooks('RegistryTestModule'))->toHaveCount(1);
    expect($verticalPackRegistry->packs('RegistryTestModule'))->toHaveCount(2);
    expect($verticalPackRegistry->default('RegistryTestModule'))->toBe('field-service');
    expect($verticalPackRegistry->find('cleaning')['config']['label'])->toBe('Cleaning');
    expect((new VerticalResolver($verticalPackRegistry))->resolve([
        'request' => ['query' => ['vertical' => 'cleaning']],
    ], 'RegistryTestModule'))->toBe('cleaning');
    expect((new VerticalResolver($verticalPackRegistry))->resolve([], 'RegistryTestModule'))->toBe('field-service');

    expect($billingRegistry->plans('RegistryTestModule'))->toHaveCount(1);
    expect($billingRegistry->meters('RegistryTestModule'))->toHaveCount(1);
    expect($billingRegistry->limits('RegistryTestModule'))->toHaveCount(1);
    expect($billingRegistry->findPlan('registry.plan.pro')['name'])->toBe('Registry Pro');
    expect($billingRegistry->findMeter('registry.usage')['class'])->toBe('Modules\\RegistryTestModule\\Billing\\Meters\\RegistryUsageMeter');
    expect($billingRegistry->findLimit('registry.projects')['value'])->toBe(10);

    expect($searchRegistry->indexes('RegistryTestModule'))->toHaveCount(1);
    expect($searchRegistry->find('registry.orders')['fields'])->toBe(['reference', 'customer_name']);

    expect($tenancyRegistry->resolvers('RegistryTestModule'))->toHaveCount(1);
    expect($tenancyRegistry->policies('RegistryTestModule'))->toHaveCount(1);
    expect($tenancyRegistry->findResolver('registry.tenant')['class'])->toBe('Modules\\RegistryTestModule\\Tenancy\\Resolvers\\RegistryTenantResolver');
    expect($tenancyRegistry->findPolicy('registry.access')['class'])->toBe('Modules\\RegistryTestModule\\Tenancy\\Policies\\RegistryTenantPolicy');

    expect($filamentRegistry->resources('DisabledModule'))->toBeEmpty();
    expect($workflowRegistry->byModule('DisabledModule'))->toBeEmpty();
    expect($automationRegistry->handlers('DisabledModule'))->toBeEmpty();
    expect($verticalPackRegistry->packs('DisabledModule'))->toBeEmpty();
    expect($billingRegistry->plans('DisabledModule'))->toBeEmpty();
    expect($searchRegistry->indexes('DisabledModule'))->toBeEmpty();
    expect($tenancyRegistry->resolvers('DisabledModule'))->toBeEmpty();

    expect($verticalPackRegistry->find('missing-vertical'))->toBeNull();
    expect($billingRegistry->findPlan('missing-plan'))->toBeNull();
    expect($billingRegistry->findMeter('missing-meter'))->toBeNull();
    expect($billingRegistry->findLimit('missing-limit'))->toBeNull();
    expect($searchRegistry->find('missing-index'))->toBeNull();
    expect($tenancyRegistry->findResolver('missing-resolver'))->toBeNull();
    expect($tenancyRegistry->findPolicy('missing-policy'))->toBeNull();
    expect((new VerticalResolver($verticalPackRegistry))->resolve([
        'request' => ['query' => ['vertical' => 'missing-vertical']],
    ], 'RegistryTestModule'))->toBeNull();

    deleteDirectory($base);
});
