<?php

use App\Providers\TitanModuleServiceProvider;
use App\Support\FeatureRegistry;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Modules\TitanCore\Support\ModuleDependencyGraph;
use Nwidart\Modules\Module;

function makeDiscoveryModuleStub(string $name, array $manifest = []): Module
{
    $stub = Mockery::mock(Module::class);
    $stub->allows('getName')->andReturn($name);
    $stub->allows('get')->with(Mockery::any())->andReturnUsing(function (string $lookupKey) use ($manifest) {
        $value = $manifest;
        foreach (explode('.', $lookupKey) as $segment) {
            if (! is_array($value) || ! array_key_exists($segment, $value)) {
                return null;
            }
            $value = $value[$segment];
        }

        return $value;
    });

    return $stub;
}

function makeDiscoveryProvider(): TitanModuleServiceProvider
{
    return new class(app()) extends TitanModuleServiceProvider
    {
        public function testRegisterDeclaredModuleProviders(Module $module): void
        {
            $this->registerDeclaredModuleProviders($module);
        }

        public function testRegisterDeclaredModuleFeatures(Module $module): void
        {
            $this->registerDeclaredModuleFeatures($module);
        }
    };
}

beforeEach(function () {
    app()->instance('titan.features', new FeatureRegistry());
    app()->instance('titan.module_boot_failures', collect());

    $graph = Mockery::mock(ModuleDependencyGraph::class);
    $graph->allows('build');
    $graph->allows('detectCycles')->andReturn([]);
    $graph->allows('getAllIssues')->andReturn([]);
    $graph->allows('resolveLoadOrder')->andReturn([]);
    $graph->allows('getNodes')->andReturn([]);
    app()->instance(ModuleDependencyGraph::class, $graph);
});

test('feature registry is accessible from the container', function () {
    expect(app()->bound('titan.features'))->toBeTrue();
    expect(app('titan.features'))->toBeInstanceOf(FeatureRegistry::class);
});

test('auto-discovery registers providers declared in module manifests', function () {
    app()->forgetInstance('titan.test.discovered_provider_loaded');
    $discoveredProviderClass = new class extends ServiceProvider
    {
        public function register(): void
        {
            app()->instance('titan.test.discovered_provider_loaded', true);
        }
    };

    $provider = makeDiscoveryProvider();
    $provider->testRegisterDeclaredModuleProviders(
        makeDiscoveryModuleStub('DemoModule', ['providers' => [$discoveredProviderClass::class]])
    );

    expect(app()->bound('titan.test.discovered_provider_loaded'))->toBeTrue();
    expect(app('titan.test.discovered_provider_loaded'))->toBeTrue();
});

test('broken module providers are skipped and logged in safe-boot mode', function () {
    Log::spy();

    $provider = makeDiscoveryProvider();
    $provider->testRegisterDeclaredModuleProviders(
        makeDiscoveryModuleStub('BrokenModule', ['providers' => ['Modules\\Broken\\Providers\\MissingProvider']])
    );

    $failures = app('titan.module_boot_failures')->all();

    expect($failures)->toBeArray()
        ->and($failures)->toHaveCount(1)
        ->and($failures[0]['module'])->toBe('BrokenModule')
        ->and($failures[0]['provider'])->toBe('Modules\\Broken\\Providers\\MissingProvider');

    Log::shouldHaveReceived('warning')->atLeast()->once();
});

test('feature registry stores and retrieves features across modules', function () {
    $provider = makeDiscoveryProvider();

    $provider->testRegisterDeclaredModuleFeatures(
        makeDiscoveryModuleStub('ModuleOne', [
            'capabilities' => ['capability.alpha', 'capability.beta'],
        ])
    );

    $provider->testRegisterDeclaredModuleFeatures(
        makeDiscoveryModuleStub('ModuleTwo', [
            'features' => [
                'feature.gamma' => ['enabled' => true],
                'feature.delta',
            ],
        ])
    );

    /** @var FeatureRegistry $features */
    $features = app('titan.features');

    expect($features->has('capability.alpha'))->toBeTrue()
        ->and($features->has('capability.beta'))->toBeTrue()
        ->and($features->get('feature.gamma'))->toBe(['enabled' => true])
        ->and($features->get('feature.delta'))->toBeTrue();
});

test('duplicate feature keys log warnings instead of crashing', function () {
    Log::spy();
    $provider = makeDiscoveryProvider();

    $provider->testRegisterDeclaredModuleFeatures(
        makeModuleStub('ModuleOne', ['capabilities' => ['feature.duplicate']])
    );
    $provider->testRegisterDeclaredModuleFeatures(
        makeModuleStub('ModuleTwo', ['features' => ['feature.duplicate']])
    );

    /** @var FeatureRegistry $features */
    $features = app('titan.features');

    expect($features->owner('feature.duplicate'))->toBe('ModuleOne')
        ->and($features->warnings())->not->toBeEmpty();

    Log::shouldHaveReceived('warning')->atLeast()->once();
});

test('safe-boot failures appear in modules doctor output', function () {
    app()->instance('titan.module_boot_failures', collect([[
        'module' => 'BrokenModule',
        'provider' => 'Modules\\Broken\\Providers\\MissingProvider',
        'error' => 'Class not found',
    ]]));

    $exitCode = Artisan::call('modules:doctor --skip-schema');
    $output = Artisan::output();

    expect($exitCode)->toBe(1);
    expect($output)->toContain('Safe-boot provider failures detected')
        ->toContain('BrokenModule')
        ->toContain('Modules\\Broken\\Providers\\MissingProvider');
});

test('modules doctor succeeds when no safe-boot failures are recorded', function () {
    app()->instance('titan.module_boot_failures', collect());

    $exitCode = Artisan::call('modules:doctor --skip-schema');
    $output = Artisan::output();

    expect($exitCode)->toBe(0);
    expect($output)->toContain('No safe-boot provider failures');
});
