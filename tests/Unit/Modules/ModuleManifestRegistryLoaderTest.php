<?php

use App\Platform\AI\AIManifestRegistry;
use App\Platform\AI\BlueprintAIManifestRegistry;
use App\Platform\Automation\AutomationRegistry;
use App\Platform\Filament\FilamentRegistry;
use App\Platform\Modules\ModuleManifestRegistryLoader;
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

/**
 * Build a ModuleManifestRegistryLoader with fresh registry instances and
 * return the loader plus all registries for inspection.
 *
 * @return array{loader: ModuleManifestRegistryLoader, filament: FilamentRegistry, workflow: WorkflowDefinitionRegistry, automation: AutomationRegistry, ai: AIManifestRegistry, blueprint: BlueprintAIManifestRegistry}
 */
function makeLoader(): array
{
    $filamentRegistry    = new FilamentRegistry;
    $workflowRegistry    = new WorkflowDefinitionRegistry;
    $automationRegistry  = new AutomationRegistry;
    $aiRegistry          = new AIManifestRegistry;
    $blueprintAIRegistry = new BlueprintAIManifestRegistry;

    $loader = new ModuleManifestRegistryLoader(
        $filamentRegistry,
        $workflowRegistry,
        $automationRegistry,
        $aiRegistry,
        $blueprintAIRegistry,
    );

    return [
        'loader'    => $loader,
        'filament'  => $filamentRegistry,
        'workflow'  => $workflowRegistry,
        'automation' => $automationRegistry,
        'ai'        => $aiRegistry,
        'blueprint' => $blueprintAIRegistry,
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

    ['loader' => $loader, 'filament' => $filamentRegistry, 'workflow' => $workflowRegistry, 'automation' => $automationRegistry] = makeLoader();

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

    expect($filamentRegistry->resources('DisabledModule'))->toBeEmpty();
    expect($workflowRegistry->byModule('DisabledModule'))->toBeEmpty();
    expect($automationRegistry->handlers('DisabledModule'))->toBeEmpty();

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

