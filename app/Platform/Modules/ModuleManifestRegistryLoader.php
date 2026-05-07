<?php

namespace App\Platform\Modules;

use App\Platform\Automation\AutomationRegistry;
use App\Platform\Billing\BillingRegistry;
use App\Platform\Filament\FilamentRegistry;
use App\Platform\Search\SearchRegistry;
use App\Platform\Tenancy\TenancyRegistry;
use App\Platform\Verticals\VerticalPackRegistry;
use App\Platform\Workflows\WorkflowDefinitionRegistry;

class ModuleManifestRegistryLoader
{
    /**
     * @var array<string, bool>
     */
    private array $loadedModules = [];

    public function __construct(
        private readonly FilamentRegistry $filamentRegistry,
        private readonly WorkflowDefinitionRegistry $workflowRegistry,
        private readonly AutomationRegistry $automationRegistry,
        private readonly VerticalPackRegistry $verticalPackRegistry,
        private readonly BillingRegistry $billingRegistry,
        private readonly SearchRegistry $searchRegistry,
        private readonly TenancyRegistry $tenancyRegistry,
    ) {}

    public function load(string $modulesPath): void
    {
        if (! is_dir($modulesPath)) {
            return;
        }

        $statusMap = $this->moduleStatusMap($modulesPath);
        $directories = glob($modulesPath.'/*', GLOB_ONLYDIR) ?: [];

        foreach ($directories as $moduleDir) {
            $module = basename($moduleDir);

            if ($this->loadedModules[$module] ?? false) {
                continue;
            }

            if (! $this->isModuleEnabled($moduleDir, $module, $statusMap)) {
                continue;
            }

            $this->loadFilamentManifest($moduleDir, $module);
            $this->loadWorkflowManifest($moduleDir, $module);
            $this->loadAutomationManifest($moduleDir, $module);
            $this->loadVerticalManifest($moduleDir, $module);
            $this->loadBillingManifest($moduleDir, $module);
            $this->loadSearchManifest($moduleDir, $module);
            $this->loadTenancyManifest($moduleDir, $module);

            $this->loadedModules[$module] = true;
        }
    }

    /**
     * @return array<string, bool>
     */
    private function moduleStatusMap(string $modulesPath): array
    {
        $statusFiles = [
            dirname($modulesPath).'/module_statuses.json',
            dirname($modulesPath).'/modules_statuses.json',
        ];

        foreach ($statusFiles as $statusFile) {
            if (! is_file($statusFile)) {
                continue;
            }

            $decoded = $this->decodeJsonFile($statusFile);

            if (! is_array($decoded)) {
                return [];
            }

            return array_map(fn (mixed $value): bool => (bool) $value, $decoded);
        }

        return [];
    }

    /**
     * @param  array<string, bool>  $statusMap
     */
    private function isModuleEnabled(string $moduleDir, string $module, array $statusMap): bool
    {
        if (array_key_exists($module, $statusMap) && $statusMap[$module] === false) {
            return false;
        }

        $moduleJson = $this->readJson($moduleDir.'/module.json');
        if (! is_array($moduleJson)) {
            if (is_file($moduleDir.'/module.json')) {
                logger()->warning('Unable to parse module.json while loading manifest registries.', [
                    'module' => $module,
                    'path' => $moduleDir.'/module.json',
                ]);
            }

            return true;
        }

        if (array_key_exists('active', $moduleJson) && (int) $moduleJson['active'] === 0) {
            return false;
        }

        if (array_key_exists('enabled', $moduleJson) && $moduleJson['enabled'] === false) {
            return false;
        }

        return true;
    }

    private function loadFilamentManifest(string $moduleDir, string $module): void
    {
        $moduleJson = $this->readJson($moduleDir.'/module.json');
        $uiManifest = $this->readJson($moduleDir.'/manifests/ui.manifest.json');

        if (($uiManifest['enabled'] ?? true) === false) {
            return;
        }

        $filamentFromModuleJson = is_array($moduleJson['filament'] ?? null) ? $moduleJson['filament'] : [];
        $filamentFromUiManifest = [];

        if (is_array($uiManifest)) {
            $filamentFromUiManifest = is_array($uiManifest['filament'] ?? null)
                ? $uiManifest['filament']
                : $uiManifest;
        }

        $this->filamentRegistry->registerManifest($module, [
            'resources' => $filamentFromUiManifest['resources'] ?? $filamentFromModuleJson['resources'] ?? [],
            'pages' => $filamentFromUiManifest['pages'] ?? $filamentFromModuleJson['pages'] ?? [],
            'widgets' => $filamentFromUiManifest['widgets'] ?? $filamentFromModuleJson['widgets'] ?? [],
        ]);
    }

    private function loadWorkflowManifest(string $moduleDir, string $module): void
    {
        $workflowManifest = $this->readJson($moduleDir.'/manifests/workflows.manifest.json');

        if (! is_array($workflowManifest) || ($workflowManifest['enabled'] ?? true) === false) {
            return;
        }

        $this->workflowRegistry->registerManifest($module, $workflowManifest);
    }

    private function loadAutomationManifest(string $moduleDir, string $module): void
    {
        $automationManifest = $this->readJson($moduleDir.'/manifests/automation.manifest.json');

        if (! is_array($automationManifest) || ($automationManifest['enabled'] ?? true) === false) {
            return;
        }

        $this->automationRegistry->registerManifest($module, $automationManifest);
    }

    private function loadVerticalManifest(string $moduleDir, string $module): void
    {
        $verticalManifest = $this->readJson($moduleDir.'/manifests/verticals.json');

        if (! is_array($verticalManifest) || ($verticalManifest['enabled'] ?? true) === false) {
            return;
        }

        $sourcePath = $verticalManifest['source'] ?? null;
        if (is_string($sourcePath) && $sourcePath !== '') {
            $configPath = $moduleDir.'/'.$sourcePath;

            if (is_file($configPath)) {
                $config = require $configPath;

                if (is_array($config)) {
                    $verticalManifest['supported'] = $verticalManifest['supported'] ?? ($config['supported'] ?? []);
                    $verticalManifest['default'] = $verticalManifest['default'] ?? ($config['default'] ?? null);
                }
            }
        }

        $this->verticalPackRegistry->registerManifest($module, $verticalManifest);
    }

    private function loadBillingManifest(string $moduleDir, string $module): void
    {
        $billingManifest = $this->readJson($moduleDir.'/manifests/billing.manifest.json');

        if (! is_array($billingManifest) || ($billingManifest['enabled'] ?? true) === false) {
            return;
        }

        $this->billingRegistry->registerManifest($module, $billingManifest);
    }

    private function loadSearchManifest(string $moduleDir, string $module): void
    {
        $searchManifest = $this->readJson($moduleDir.'/manifests/search.manifest.json');

        if (! is_array($searchManifest) || ($searchManifest['enabled'] ?? true) === false) {
            return;
        }

        $this->searchRegistry->registerManifest($module, $searchManifest);
    }

    private function loadTenancyManifest(string $moduleDir, string $module): void
    {
        $tenancyManifest = $this->readJson($moduleDir.'/manifests/tenancy.manifest.json');

        if (! is_array($tenancyManifest) || ($tenancyManifest['enabled'] ?? true) === false) {
            return;
        }

        $this->tenancyRegistry->registerManifest($module, $tenancyManifest);
    }

    /**
     * @return array<string, mixed>|null
     */
    private function readJson(string $path): ?array
    {
        return $this->decodeJsonFile($path);
    }

    /**
     * @return array<string, mixed>|null
     */
    private function decodeJsonFile(string $path): ?array
    {
        if (! is_file($path)) {
            return null;
        }

        $raw = file_get_contents($path);
        if ($raw === false) {
            return null;
        }

        $decoded = json_decode($raw, true);

        return is_array($decoded) ? $decoded : null;
    }
}
