<?php

namespace App\Platform\Modules;

use App\Platform\AI\AIManifestRegistry;
use App\Platform\AI\BlueprintAIManifestRegistry;
use App\Platform\Automation\AutomationRegistry;
use App\Platform\Filament\FilamentRegistry;
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
        private readonly AIManifestRegistry $aiRegistry,
        private readonly BlueprintAIManifestRegistry $blueprintAIRegistry,
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
            $this->loadAIManifest($moduleDir, $module);
            $this->loadBlueprintAIManifest($moduleDir, $module);

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

    private function loadAIManifest(string $moduleDir, string $module): void
    {
        $aiManifest = $this->readJson($moduleDir.'/manifests/ai.manifest.json');

        if (! is_array($aiManifest) || ($aiManifest['enabled'] ?? true) === false) {
            return;
        }

        $this->aiRegistry->registerManifest($module, $aiManifest);
    }

    private function loadBlueprintAIManifest(string $moduleDir, string $module): void
    {
        $aiDir = $moduleDir.'/AI';

        $blueprint = [
            'agents'     => $this->discoverAgentManifests($moduleDir),
            'indexing'   => $this->readJson($aiDir.'/Indexing/indexing.manifest.json'),
            'retrieval'  => $this->readJson($aiDir.'/Retrieval/retrieval.policy.json'),
            'citations'  => $this->readJson($aiDir.'/Citations/citation.schema.json'),
            'guardrails' => $this->readJson($aiDir.'/Guardrails/guardrails.json'),
            'actions'    => $this->readJson($aiDir.'/Actions/action-map.json'),
            'telemetry'  => $this->readJson($aiDir.'/Telemetry/telemetry.manifest.json'),
            'control'    => $this->readJson($aiDir.'/Control/control.manifest.json'),
        ];

        // Only register if at least one blueprint section has data.
        $hasData = ! empty(array_filter($blueprint));

        if (! $hasData) {
            return;
        }

        $this->blueprintAIRegistry->registerManifest($module, $blueprint);
    }

    /**
     * Discover all agent.manifest.json files under each Agents/ subdirectory.
     *
     * @return array<int, array<string, mixed>>
     */
    private function discoverAgentManifests(string $moduleDir): array
    {
        $agentsDir = $moduleDir.'/Agents';
        $agents    = [];

        if (! is_dir($agentsDir)) {
            return $agents;
        }

        foreach (glob($agentsDir.'/*/agent.manifest.json') ?: [] as $manifestPath) {
            $data = $this->readJson($manifestPath);
            if (is_array($data)) {
                $agents[] = $data;
            }
        }

        return $agents;
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
