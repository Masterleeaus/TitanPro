<?php

use App\Platform\Automation\AutomationRegistry;
use App\Platform\Filament\FilamentRegistry;
use App\Platform\Modules\ChannelManifestRegistry;
use App\Platform\Modules\DashboardRegistry;
use App\Platform\Modules\ModuleManifestRegistryLoader;
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

    $filamentRegistry = new FilamentRegistry;
    $workflowRegistry = new WorkflowDefinitionRegistry;
    $automationRegistry = new AutomationRegistry;
    $pwaRegistry = new PwaManifestRegistry;
    $channelRegistry = new ChannelManifestRegistry;
    $omniRegistry = new OmniManifestRegistry;
    $voiceRegistry = new VoiceManifestRegistry;
    $uiKitRegistry = new UiKitRegistry;
    $dashboardRegistry = new DashboardRegistry;
    $tableRegistry = new TableRegistry;
    $shortcutRegistry = new ShortcutRegistry;
    $settingsRegistry = new SettingsRegistry;
    $loader = new ModuleManifestRegistryLoader(
        $filamentRegistry,
        $workflowRegistry,
        $automationRegistry,
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
    expect($pwaRegistry->byModule('DisabledModule'))->toBeEmpty();
    expect($uiKitRegistry->byModule('DisabledModule'))->toBeEmpty();
    expect($settingsRegistry->byModule('DisabledModule'))->toBeEmpty();

    deleteDirectory($base);
});
