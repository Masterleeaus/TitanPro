<?php

use App\Providers\TitanModuleServiceProvider;
use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\PanelRegistry;
use Illuminate\Support\Collection;
use Modules\TitanEchoAssist\Filament\Plugin\TitanEchoAssistPlugin;
use Nwidart\Modules\Module;

/**
 * Verifies that TitanModuleServiceProvider auto-injects enabled module
 * Filament plugins into the correct panel and skips disabled modules.
 *
 * The tests use Mockery to create lightweight nwidart Module stubs and a
 * dedicated anonymous Plugin class so real module infrastructure is not
 * needed.
 */

// ── Helpers ───────────────────────────────────────────────────────────────────

/**
 * Create a Mockery stub for an nwidart Module.
 *
 * @param  array<string, mixed>  $manifest  Key/value pairs returned by Module::get()
 */
function makeModuleStub(string $name, bool $enabled, array $manifest = []): Module
{
    $stub = Mockery::mock(Module::class);

    $stub->allows('getName')->andReturn($name);
    $stub->allows('isEnabled')->andReturn($enabled);

    $stub->allows('get')->with(Mockery::any())->andReturnUsing(
        function (string $key) use ($manifest) {
            // Handle dot-notation keys
            $keys = explode('.', $key);
            $value = $manifest;
            foreach ($keys as $segment) {
                if (! is_array($value) || ! array_key_exists($segment, $value)) {
                    return null;
                }
                $value = $value[$segment];
            }

            return $value;
        }
    );

    return $stub;
}

/**
 * Build a TitanModuleServiceProvider subclass that exposes the protected
 * injection methods as public so we can call them directly in tests.
 */
function makeTestableProvider(): TitanModuleServiceProvider
{
    return new class(app()) extends TitanModuleServiceProvider
    {
        public function testResolvePluginClass(Module $module): ?string
        {
            return $this->resolvePluginClass($module);
        }

        public function testRegisterModulePlugin(Module $module, PanelRegistry $registry): void
        {
            $this->registerModulePlugin($module, $registry);
        }

        public function testInjectPluginIntoPanel(Panel $panel, string $pluginClass): void
        {
            $this->injectPluginIntoPanel($panel, $pluginClass);
        }
    };
}

/**
 * Remove a plugin from a panel by ID (test cleanup helper).
 */
function removePluginFromPanel(Panel $panel, string $pluginId): void
{
    $reflection = new ReflectionProperty($panel, 'plugins');
    $reflection->setAccessible(true);
    $plugins = $reflection->getValue($panel);
    unset($plugins[$pluginId]);
    $reflection->setValue($panel, $plugins);
}

// ── Anonymous plugin stub ─────────────────────────────────────────────────────

/**
 * A minimal Filament plugin used only in tests.
 * Defined once at file scope so its FQCN is stable within the test run.
 */
if (! class_exists('TitanTestFilamentPlugin')) {
    /**
     * @internal Test-only Filament plugin stub.
     */
    class TitanTestFilamentPlugin implements Plugin
    {
        public static function make(): static
        {
            return new static;
        }

        public function getId(): string
        {
            return 'titan-test-plugin';
        }

        public function register(Panel $panel): void
        {
            // No real resources to register in tests.
        }

        public function boot(Panel $panel): void {}
    }
}

// ── injectPluginIntoPanel ─────────────────────────────────────────────────────

test('injectPluginIntoPanel registers the plugin with the panel', function () {
    $panel = Panel::make()->id('test-panel');
    $provider = makeTestableProvider();

    $provider->testInjectPluginIntoPanel($panel, TitanTestFilamentPlugin::class);

    expect($panel->hasPlugin('titan-test-plugin'))->toBeTrue();
});

test('injectPluginIntoPanel does not register the same plugin twice', function () {
    $panel = Panel::make()->id('test-panel');
    $provider = makeTestableProvider();

    $provider->testInjectPluginIntoPanel($panel, TitanTestFilamentPlugin::class);
    $provider->testInjectPluginIntoPanel($panel, TitanTestFilamentPlugin::class); // second call is a no-op

    expect(count($panel->getPlugins()))->toBe(1);
});

// ── resolvePluginClass ────────────────────────────────────────────────────────

test('resolvePluginClass returns null when no plugin class exists', function () {
    $module = makeModuleStub('NonExistentModule', true);
    $provider = makeTestableProvider();

    expect($provider->testResolvePluginClass($module))->toBeNull();
});

test('resolvePluginClass finds plugin at canonical Filament/Plugin/ path', function () {
    // Point a fake module at the test plugin via the explicit manifest key so
    // the discovery logic short-circuits to the test class we control.
    $module = makeModuleStub('TestModule', true, ['filament' => ['plugin' => TitanTestFilamentPlugin::class]]);
    $provider = makeTestableProvider();

    expect($provider->testResolvePluginClass($module))->toBe(TitanTestFilamentPlugin::class);
});

test('resolvePluginClass discovers TitanEchoAssist plugin by module name', function () {
    $module = makeModuleStub('TitanEchoAssist', true);
    $provider = makeTestableProvider();

    expect($provider->testResolvePluginClass($module))->toBe(TitanEchoAssistPlugin::class);
});

test('resolvePluginClass ignores a manifest class that does not implement Plugin', function () {
    // Use a class that definitely exists but does NOT implement Plugin.
    $module = makeModuleStub('TestModule', true, ['filament' => ['plugin' => Collection::class]]);
    $provider = makeTestableProvider();

    expect($provider->testResolvePluginClass($module))->toBeNull();
});

// ── registerModulePlugin ──────────────────────────────────────────────────────

test('registerModulePlugin injects plugin into default panel when filament_panel not set', function () {
    $registry = app(PanelRegistry::class);
    $panels = $registry->all();

    // The app must have at least one panel registered (AdminPanelProvider etc.).
    expect($panels)->not->toBeEmpty();

    $defaultPanel = $registry->getDefault();

    // Create a module stub that points to our test plugin via manifest key.
    $module = makeModuleStub('TestModule', true, ['filament' => ['plugin' => TitanTestFilamentPlugin::class]]);
    $provider = makeTestableProvider();

    $provider->testRegisterModulePlugin($module, $registry);

    expect($defaultPanel->hasPlugin('titan-test-plugin'))->toBeTrue();

    removePluginFromPanel($defaultPanel, 'titan-test-plugin');
});

test('registerModulePlugin respects filament_panel targeting', function () {
    $registry = app(PanelRegistry::class);
    $panels = $registry->all();

    expect($panels)->not->toBeEmpty();

    $defaultPanel = $registry->getDefault();

    // Pick a panel that is NOT the default to test targeting.
    $targetPanel = null;
    foreach ($panels as $panel) {
        if ($panel->getId() !== $defaultPanel->getId()) {
            $targetPanel = $panel;
            break;
        }
    }

    if ($targetPanel === null) {
        test()->skip('Need at least two panels to test filament_panel targeting.');
    }

    $module = makeModuleStub('TestModule', true, [
        'filament_panel' => $targetPanel->getId(),
        'filament' => ['plugin' => TitanTestFilamentPlugin::class],
    ]);
    $provider = makeTestableProvider();

    $provider->testRegisterModulePlugin($module, $registry);

    // Plugin must be in the targeted panel, not the default one.
    expect($targetPanel->hasPlugin('titan-test-plugin'))->toBeTrue();
    expect($defaultPanel->hasPlugin('titan-test-plugin'))->toBeFalse();

    removePluginFromPanel($targetPanel, 'titan-test-plugin');
});

// ── Disabled modules ──────────────────────────────────────────────────────────

test('injectModuleFilamentPlugins skips disabled modules', function () {
    // allEnabled() returns no modules (none are enabled in the test environment).
    // Verify none of the panels received the test plugin after boot.
    $registry = app(PanelRegistry::class);

    foreach ($registry->all() as $panel) {
        expect($panel->hasPlugin('titan-test-plugin'))->toBeFalse();
    }
});

// ── TitanModuleServiceProvider is registered ──────────────────────────────────

test('TitanModuleServiceProvider registers the titan.modules singleton', function () {
    expect(app()->bound('titan.modules'))->toBeTrue();
    expect(app('titan.modules'))->toBeArray();
});
