<?php

namespace Modules\Accountings\Tests\Unit;

use Modules\Accountings\Agents\MoneyAgent;
use Tests\TestCase;

class ManifestConsistencyTest extends TestCase
{
    public function test_module_json_has_single_active_key(): void
    {
        $moduleJson = file_get_contents(base_path('Modules/Accountings/module.json'));

        $this->assertSame(1, substr_count((string) $moduleJson, '"active"'));
    }

    public function test_declared_providers_resolve_to_existing_classes(): void
    {
        $manifest = json_decode((string) file_get_contents(base_path('Modules/Accountings/module.json')), true, 512, JSON_THROW_ON_ERROR);

        foreach ($manifest['providers'] as $providerClass) {
            $this->assertTrue(class_exists($providerClass), "Provider class [$providerClass] does not exist.");
        }
    }

    public function test_accounting_action_and_tool_maps_are_consistent(): void
    {
        $actionMap = json_decode((string) file_get_contents(base_path('Modules/Accountings/AI/Actions/action-map.json')), true, 512, JSON_THROW_ON_ERROR);
        $aiTools = json_decode((string) file_get_contents(base_path('Modules/Accountings/manifests/ai-tools.json')), true, 512, JSON_THROW_ON_ERROR);

        $actionKeys = array_keys($actionMap);
        sort($actionKeys);

        $toolNames = array_map(static fn (array $tool): string => $tool['name'], $aiTools['tools']);
        sort($toolNames);

        $this->assertSame($actionKeys, $toolNames);

        $agent = new MoneyAgent;
        $registeredToolNames = array_map(function (string $toolClass): string {
            return app($toolClass)->name();
        }, $agent->tools());
        sort($registeredToolNames);

        $this->assertSame($actionKeys, $registeredToolNames);
    }

    public function test_accountings_service_provider_does_not_double_register_route_provider(): void
    {
        $providerSource = file_get_contents(base_path('Modules/Accountings/Providers/AccountingsServiceProvider.php'));

        $this->assertStringNotContainsString('register(RouteServiceProvider::class)', (string) $providerSource);
    }
}
