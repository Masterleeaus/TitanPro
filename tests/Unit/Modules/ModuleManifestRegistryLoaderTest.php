<?php

use App\Platform\AI\AIManifestRegistry;
use App\Platform\AI\BlueprintAIManifestRegistry;
use App\Platform\Automation\AutomationRegistry;
use App\Platform\Billing\BillingRegistry;
use App\Platform\Filament\FilamentRegistry;
use App\Platform\Modules\ChannelManifestRegistry;
use App\Platform\Modules\DashboardRegistry;
use App\Platform\Modules\ModuleManifestRegistryLoader;
use App\Platform\Search\SearchRegistry;
use App\Platform\Tenancy\TenancyRegistry;
use App\Platform\Verticals\VerticalPackRegistry;
use App\Platform\Verticals\VerticalResolver;
use App\Platform\Modules\OmniManifestRegistry;
use App\Platform\Modules\PwaManifestRegistry;
use App\Platform\Modules\SettingsRegistry;
use App\Platform\Modules\ShortcutRegistry;
use App\Platform\Modules\TableRegistry;
use App\Platform\Modules\UiKitRegistry;
use App\Platform\Modules\VoiceManifestRegistry;
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

function writePhpArrayFile(string $path, array $data): void
{
    if (! is_dir(dirname($path))) {
        mkdir(dirname($path), 0755, true);
    }

    file_put_contents($path, "<?php\n\nreturn ".var_export($data, true).";\n");
}

function makeLoader(): array
{
    $filamentRegistry    = new FilamentRegistry;
    $workflowRegistry    = new WorkflowDefinitionRegistry;
    $automationRegistry  = new AutomationRegistry;
    $verticalPackRegistry = new VerticalPackRegistry;
    $billingRegistry     = new BillingRegistry;
    $searchRegistry      = new SearchRegistry;
    $tenancyRegistry     = new TenancyRegistry;
    $aiRegistry          = new AIManifestRegistry;
    $blueprintAIRegistry = new BlueprintAIManifestRegistry;
    $pwaRegistry         = new PwaManifestRegistry;
    $channelRegistry     = new ChannelManifestRegistry;
    $omniRegistry        = new OmniManifestRegistry;
    $voiceRegistry       = new VoiceManifestRegistry;
    $uiKitRegistry       = new UiKitRegistry;
    $dashboardRegistry   = new DashboardRegistry;
    $tableRegistry       = new TableRegistry;
    $shortcutRegistry    = new ShortcutRegistry;
    $settingsRegistry    = new SettingsRegistry;

    $loader = new ModuleManifestRegistryLoader(
        $filamentRegistry,
        $workflowRegistry,
        $automationRegistry,
        $verticalPackRegistry,
        $billingRegistry,
        $searchRegistry,
        $tenancyRegistry,
        $aiRegistry,
        $blueprintAIRegistry,
        $pwaRegistry,
        $channelRegistry,
        $omniRegistry,
        $voiceRegistry,
        $uiKitRegistry,
        $dashboardRegistry,
        $tableRegistry,
        $shortcutRegistry,
        $settingsRegistry,
    );

    return [
        'loader'          => $loader,
        'filament'        => $filamentRegistry,
        'workflow'        => $workflowRegistry,
        'automation'      => $automationRegistry,
        'vertical'        => $verticalPackRegistry,
        'billing'         => $billingRegistry,
        'search'          => $searchRegistry,
        'tenancy'         => $tenancyRegistry,
        'ai'              => $aiRegistry,
        'blueprint'       => $blueprintAIRegistry,
        'pwa'             => $pwaRegistry,
        'channel'         => $channelRegistry,
        'omni'            => $omniRegistry,
        'voice'           => $voiceRegistry,
        'uiKit'           => $uiKitRegistry,
        'dashboard'       => $dashboardRegistry,
        'table'           => $tableRegistry,
        'shortcut'        => $shortcutRegistry,
        'settings'        => $settingsRegistry,
    ];
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
    writePhpArrayFile($modulesPath.'/RegistryTestModule/Config/verticals.php', [
        'default' => 'field-service',
        'supported' => [
            'field-service' => ['label' => 'Field Service'],
            'cleaning' => ['label' => 'Cleaning'],
        ],
    ]);
    writeJson($modulesPath.'/RegistryTestModule/PWA/pwa.manifest.json', [
        'enabled' => true,
        'screens' => ['module_dashboard'],
        'capabilities' => ['quick_actions'],
    ]);
    writeJson($modulesPath.'/RegistryTestModule/AI/Channels/channel.manifest.json', [
        'channels' => ['web', 'sms'],
        'events' => ['registry.channel.updated'],
    ]);
    writeJson($modulesPath.'/RegistryTestModule/manifests/omni.json', [
        'channels' => ['email', 'push'],
    ]);
    writeJson($modulesPath.'/RegistryTestModule/AI/Voice/voice.manifest.json', [
        'pipeline' => ['speech_to_text', 'text_to_speech'],
    ]);
    writeJson($modulesPath.'/RegistryTestModule/manifests/ui-kit.manifest.json', [
        'components' => [
            ['key' => 'registry.card', 'component' => 'RegistryCard'],
        ],
    ]);
    writeJson($modulesPath.'/RegistryTestModule/manifests/dashboard.manifest.json', [
        'widgets' => ['RegistryWidget'],
        'layouts' => [
            ['key' => 'registry.layout', 'columns' => 2],
        ],
    ]);
    writeJson($modulesPath.'/RegistryTestModule/manifests/table.manifest.json', [
        'tables' => [
            ['key' => 'registry.table', 'class' => 'Modules\\RegistryTestModule\\Tables\\RegistryTable'],
        ],
    ]);
    writeJson($modulesPath.'/RegistryTestModule/Filament/Shortcuts/shortcuts.json', [
        'shortcuts' => [
            ['key' => 'registry.open', 'action' => 'registry.open'],
        ],
    ]);
    writeJson($modulesPath.'/RegistryTestModule/Filament/Settings/settings.schema.json', [
        'settings' => [
            ['key' => 'registry.enabled', 'type' => 'boolean'],
        ],
    ]);

    // Enabled module with empty declarations should not throw.
    writeJson($modulesPath.'/EmptyDeclarationsModule/module.json', [
        'name' => 'EmptyDeclarationsModule',
        'active' => 1,
    ]);
    writeJson($modulesPath.'/EmptyDeclarationsModule/PWA/pwa.manifest.json', [
        'enabled' => true,
        'screens' => [],
    ]);
    writeJson($modulesPath.'/EmptyDeclarationsModule/manifests/ui-kit.manifest.json', [
        'components' => [],
    ]);
    writeJson($modulesPath.'/EmptyDeclarationsModule/Filament/Settings/settings.schema.json', [
        'settings' => [],
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
    writeJson($modulesPath.'/DisabledModule/PWA/pwa.manifest.json', [
        'screens' => ['disabled_screen'],
    ]);
    writeJson($modulesPath.'/DisabledModule/manifests/ui-kit.manifest.json', [
        'components' => [
            ['key' => 'disabled.component', 'component' => 'DisabledComponent'],
        ],
    ]);
    writeJson($modulesPath.'/DisabledModule/Filament/Settings/settings.schema.json', [
        'settings' => [
            ['key' => 'disabled.setting'],
        ],
    ]);

    [
        'loader'     => $loader,
        'filament'   => $filamentRegistry,
        'workflow'   => $workflowRegistry,
        'automation' => $automationRegistry,
        'vertical'   => $verticalPackRegistry,
        'billing'    => $billingRegistry,
        'search'     => $searchRegistry,
        'tenancy'    => $tenancyRegistry,
        'pwa'        => $pwaRegistry,
        'channel'    => $channelRegistry,
        'omni'       => $omniRegistry,
        'voice'      => $voiceRegistry,
        'uiKit'      => $uiKitRegistry,
        'dashboard'  => $dashboardRegistry,
        'table'      => $tableRegistry,
        'shortcut'   => $shortcutRegistry,
        'settings'   => $settingsRegistry,
    ] = makeLoader();

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

    expect($pwaRegistry->manifest('RegistryTestModule'))->not->toBeNull();
    expect($pwaRegistry->byType('screen', 'RegistryTestModule'))->toHaveCount(1);
    expect($pwaRegistry->mergeIntoGlobalManifest(['name' => 'FieldOps'])['modules'])->toHaveKey('RegistryTestModule');
    expect($channelRegistry->byType('channel', 'RegistryTestModule'))->toHaveCount(2);
    expect($omniRegistry->byType('channel', 'RegistryTestModule'))->toHaveCount(2);
    expect($voiceRegistry->byType('pipeline_step', 'RegistryTestModule'))->toHaveCount(2);
    expect($uiKitRegistry->components('RegistryTestModule'))->toHaveCount(1);
    expect($uiKitRegistry->find('RegistryTestModule', 'registry.card'))->not->toBeNull();
    expect($dashboardRegistry->byType('widget', 'RegistryTestModule'))->toHaveCount(1);
    expect($tableRegistry->find('RegistryTestModule', 'registry.table'))->not->toBeNull();
    expect($shortcutRegistry->find('RegistryTestModule', 'registry.open'))->not->toBeNull();
    expect($settingsRegistry->find('RegistryTestModule', 'registry.enabled'))->not->toBeNull();

    expect($pwaRegistry->byModule('EmptyDeclarationsModule'))->toBeEmpty();
    expect($uiKitRegistry->components('EmptyDeclarationsModule'))->toBeEmpty();
    expect($settingsRegistry->byModule('EmptyDeclarationsModule'))->toBeEmpty();

    expect($filamentRegistry->resources('DisabledModule'))->toBeEmpty();
    expect($workflowRegistry->byModule('DisabledModule'))->toBeEmpty();
    expect($automationRegistry->handlers('DisabledModule'))->toBeEmpty();

    expect($verticalPackRegistry->packs('DisabledModule'))->toBeEmpty();
    expect($billingRegistry->plans('DisabledModule'))->toBeEmpty();
    expect($searchRegistry->indexes('DisabledModule'))->toBeEmpty();
    expect($tenancyRegistry->resolvers('DisabledModule'))->toBeEmpty();

    expect($verticalPackRegistry->find('missing-vertical'))->toBeNull();
    expect($verticalPackRegistry->find('disabled'))->toBeNull();
    expect($billingRegistry->findPlan('missing-plan'))->toBeNull();
    expect($billingRegistry->findPlan('disabled.plan'))->toBeNull();
    expect($billingRegistry->findMeter('missing-meter'))->toBeNull();
    expect($billingRegistry->findMeter('disabled.meter'))->toBeNull();
    expect($billingRegistry->findLimit('missing-limit'))->toBeNull();
    expect($billingRegistry->findLimit('disabled.limit'))->toBeNull();
    expect($searchRegistry->find('missing-index'))->toBeNull();
    expect($searchRegistry->find('disabled.index'))->toBeNull();
    expect($tenancyRegistry->findResolver('missing-resolver'))->toBeNull();
    expect($tenancyRegistry->findResolver('disabled.resolver'))->toBeNull();
    expect($tenancyRegistry->findPolicy('missing-policy'))->toBeNull();
    expect($tenancyRegistry->findPolicy('disabled.policy'))->toBeNull();
    expect((new VerticalResolver($verticalPackRegistry))->resolve([
        'request' => ['query' => ['vertical' => 'missing-vertical']],
    ], 'RegistryTestModule'))->toBeNull();

    expect($pwaRegistry->byModule('DisabledModule'))->toBeEmpty();
    expect($uiKitRegistry->byModule('DisabledModule'))->toBeEmpty();
    expect($settingsRegistry->byModule('DisabledModule'))->toBeEmpty();

    deleteDirectory($base);
});

test('manifest loader loads AI manifest agents, tools, prompts, memory, and catalog', function () {
    $base        = sys_get_temp_dir().'/titan_ai_loader_'.uniqid('', true);
    $modulesPath = $base.'/Modules';

    mkdir($modulesPath, 0755, true);

    writeJson($modulesPath.'/AITestModule/module.json', ['name' => 'AITestModule', 'active' => 1]);

    writeJson($modulesPath.'/AITestModule/manifests/ai.manifest.json', [
        'module'  => 'AITestModule',
        'enabled' => true,
        'agents'  => [
            'Modules\\AITestModule\\AI\\Agents\\ChatAgent',
            'Modules\\AITestModule\\AI\\Agents\\BookingAgent',
        ],
        'tools' => [
            ['name' => 'create_booking', 'class' => 'Modules\\AITestModule\\AI\\Tools\\CreateBookingTool'],
            'Modules\\AITestModule\\AI\\Tools\\LookupCustomerTool',
        ],
        'prompts' => [
            'system'   => 'Agents/BookingAgent/prompts/system.md',
            'answering' => 'Agents/BookingAgent/prompts/answering.md',
        ],
        'memory' => [
            'Modules\\AITestModule\\AI\\Memory\\ConversationMemory',
        ],
        'catalog' => [
            ['id' => 'knowledge.faqs', 'path' => 'Knowledge/faqs/'],
        ],
    ]);

    ['loader' => $loader, 'ai' => $aiRegistry] = makeLoader();
    $loader->load($modulesPath);

    expect($aiRegistry->agents('AITestModule'))->toBe([
        'Modules\\AITestModule\\AI\\Agents\\ChatAgent',
        'Modules\\AITestModule\\AI\\Agents\\BookingAgent',
    ]);

    $tools = $aiRegistry->tools('AITestModule');
    expect($tools)->toHaveCount(2);
    expect($tools[0]['name'])->toBe('create_booking');
    expect($tools[1]['name'])->toBe('Modules\\AITestModule\\AI\\Tools\\LookupCustomerTool');

    expect($aiRegistry->prompts('AITestModule'))->toHaveKey('system');
    expect($aiRegistry->promptByKey('answering', 'AITestModule'))->toBe('Agents/BookingAgent/prompts/answering.md');

    expect($aiRegistry->memory('AITestModule'))->toBe([
        'Modules\\AITestModule\\AI\\Memory\\ConversationMemory',
    ]);

    expect($aiRegistry->catalog('AITestModule'))->toHaveCount(1);
    expect($aiRegistry->catalog('AITestModule')[0]['id'])->toBe('knowledge.faqs');

    deleteDirectory($base);
});

test('manifest loader loads blueprint AI manifest sections from the AI directory and agent manifests', function () {
    $base        = sys_get_temp_dir().'/titan_blueprint_ai_'.uniqid('', true);
    $modulesPath = $base.'/Modules';

    mkdir($modulesPath, 0755, true);

    writeJson($modulesPath.'/BlueprintModule/module.json', ['name' => 'BlueprintModule', 'active' => 1]);

    writeJson($modulesPath.'/BlueprintModule/AI/Indexing/indexing.manifest.json', [
        'indexing' => ['strategy' => 'chunked_embedding', 'index_targets' => [['path' => 'Knowledge/']]],
    ]);
    writeJson($modulesPath.'/BlueprintModule/AI/Retrieval/retrieval.policy.json', [
        'retrieval' => ['strategy' => 'semantic_similarity', 'top_k' => 5],
    ]);
    writeJson($modulesPath.'/BlueprintModule/AI/Citations/citation.schema.json', [
        'citation' => ['fields' => ['source', 'chunk_index']],
    ]);
    writeJson($modulesPath.'/BlueprintModule/AI/Guardrails/guardrails.json', [
        'input_guardrails'  => [['id' => 'prompt_injection', 'action' => 'block']],
        'output_guardrails' => [['id' => 'no_price_guarantees', 'action' => 'rewrite']],
        'escalation_triggers' => [['trigger' => 'human_requested', 'priority' => 'normal']],
    ]);
    writeJson($modulesPath.'/BlueprintModule/AI/Actions/action-map.json', [
        'tools' => [['name' => 'blueprint.create_record', 'action' => 'CreateRecordAction']],
    ]);
    writeJson($modulesPath.'/BlueprintModule/AI/Telemetry/telemetry.manifest.json', [
        'metrics' => [['name' => 'blueprint_messages_total', 'type' => 'counter']],
    ]);
    writeJson($modulesPath.'/BlueprintModule/AI/Control/control.manifest.json', [
        'controller' => 'Modules\\BlueprintModule\\Http\\Controllers\\ModuleAgentController',
    ]);

    // Agent manifests under Agents/
    writeJson($modulesPath.'/BlueprintModule/Agents/BookingAgent/agent.manifest.json', [
        'agent_id'    => 'booking-agent',
        'agent_class' => 'Modules\\BlueprintModule\\AI\\Agents\\BookingAgent',
    ]);
    writeJson($modulesPath.'/BlueprintModule/Agents/SupportAgent/agent.manifest.json', [
        'agent_id'    => 'support-agent',
        'agent_class' => 'Modules\\BlueprintModule\\AI\\Agents\\SupportAgent',
    ]);

    ['loader' => $loader, 'blueprint' => $blueprintRegistry] = makeLoader();
    $loader->load($modulesPath);

    // Agents from Agents/ subdirectories
    $agents = $blueprintRegistry->agents('BlueprintModule');
    expect($agents)->toHaveCount(2);
    $agentIds = array_column($agents, 'agent_id');
    expect($agentIds)->toContain('booking-agent');
    expect($agentIds)->toContain('support-agent');

    // Blueprint sections
    expect($blueprintRegistry->indexing('BlueprintModule'))->toHaveKey('indexing');
    expect($blueprintRegistry->retrieval('BlueprintModule'))->toHaveKey('retrieval');
    expect($blueprintRegistry->citations('BlueprintModule'))->toHaveKey('citation');

    $guardrails = $blueprintRegistry->guardrails('BlueprintModule');
    expect($guardrails)->toHaveKey('input_guardrails');
    expect($guardrails)->toHaveKey('output_guardrails');
    expect($guardrails)->toHaveKey('escalation_triggers');

    expect($blueprintRegistry->actions('BlueprintModule'))->toHaveKey('tools');
    expect($blueprintRegistry->telemetry('BlueprintModule'))->toHaveKey('metrics');
    expect($blueprintRegistry->control('BlueprintModule'))->toHaveKey('controller');

    deleteDirectory($base);
});

test('manifest loader excludes AI and blueprint AI manifests from disabled modules', function () {
    $base        = sys_get_temp_dir().'/titan_ai_disabled_'.uniqid('', true);
    $modulesPath = $base.'/Modules';

    mkdir($modulesPath, 0755, true);

    writeJson($modulesPath.'/DisabledAIModule/module.json', ['name' => 'DisabledAIModule', 'active' => 0]);
    writeJson($modulesPath.'/DisabledAIModule/manifests/ai.manifest.json', [
        'agents' => ['Modules\\DisabledAIModule\\AI\\Agents\\SomeAgent'],
    ]);
    writeJson($modulesPath.'/DisabledAIModule/AI/Guardrails/guardrails.json', [
        'input_guardrails' => [['id' => 'some_rule', 'action' => 'block']],
    ]);

    ['loader' => $loader, 'ai' => $aiRegistry, 'blueprint' => $blueprintRegistry] = makeLoader();
    $loader->load($modulesPath);

    expect($aiRegistry->agents('DisabledAIModule'))->toBeEmpty();
    expect($blueprintRegistry->guardrails('DisabledAIModule'))->toBeNull();
    expect($blueprintRegistry->agents('DisabledAIModule'))->toBeEmpty();

    deleteDirectory($base);
});

test('manifest loader skips AI manifest when enabled flag is false', function () {
    $base        = sys_get_temp_dir().'/titan_ai_enabled_flag_'.uniqid('', true);
    $modulesPath = $base.'/Modules';

    mkdir($modulesPath, 0755, true);

    writeJson($modulesPath.'/FlaggedModule/module.json', ['name' => 'FlaggedModule', 'active' => 1]);
    writeJson($modulesPath.'/FlaggedModule/manifests/ai.manifest.json', [
        'enabled' => false,
        'agents'  => ['Modules\\FlaggedModule\\AI\\Agents\\SomeAgent'],
    ]);

    ['loader' => $loader, 'ai' => $aiRegistry] = makeLoader();
    $loader->load($modulesPath);

    expect($aiRegistry->agents('FlaggedModule'))->toBeEmpty();

    deleteDirectory($base);
});

