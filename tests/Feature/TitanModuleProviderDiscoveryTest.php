<?php

use App\Providers\TitanModuleServiceProvider;
use App\Support\FeatureRegistry;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Nwidart\Modules\Module;

if (! class_exists('TitanTestDiscoveredProvider')) {
    class TitanTestDiscoveredProvider extends ServiceProvider
    {
        public function register(): void
        {
            app()->instance('titan.test.discovered_provider_loaded', true);
        }
    }
}

function titanMakeModuleStub(string $name, array $manifest = []): Module
{
    $stub = Mockery::mock(Module::class);
    $stub->allows('getName')->andReturn($name);
    $stub->allows('get')->with(Mockery::any())->andReturnUsing(function (string $key) use ($manifest) {
        $value = $manifest;
        foreach (explode('.', $key) as $segment) {
            if (! is_array($value) || ! array_key_exists($segment, $value)) {
                return null;
            }
            $value = $value[$segment];
        }

        return $value;
    });

    return $stub;
}

function titanMakeDiscoveryProvider(): TitanModuleServiceProvider
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
    app()->instance('titan.module_boot_failures', []);
});

test('feature registry is accessible from the container', function () {
    expect(app()->bound('titan.features'))->toBeTrue();
    expect(app('titan.features'))->toBeInstanceOf(FeatureRegistry::class);
});

test('auto-discovery registers providers declared in module manifests', function () {
    app()->forgetInstance('titan.test.discovered_provider_loaded');

    $provider = titanMakeDiscoveryProvider();
    $provider->testRegisterDeclaredModuleProviders(
        titanMakeModuleStub('DemoModule', ['providers' => [TitanTestDiscoveredProvider::class]])
    );

    expect(app()->bound('titan.test.discovered_provider_loaded'))->toBeTrue();
    expect(app('titan.test.discovered_provider_loaded'))->toBeTrue();
});

test('broken module providers are skipped and logged in safe-boot mode', function () {
    Log::spy();

    $provider = titanMakeDiscoveryProvider();
    $provider->testRegisterDeclaredModuleProviders(
        titanMakeModuleStub('BrokenModule', ['providers' => ['Modules\\Broken\\Providers\\MissingProvider']])
    );

    $failures = app('titan.module_boot_failures');

    expect($failures)->toBeArray()
        ->and($failures)->toHaveCount(1)
        ->and($failures[0]['module'])->toBe('BrokenModule')
        ->and($failures[0]['provider'])->toBe('Modules\\Broken\\Providers\\MissingProvider');

    Log::shouldHaveReceived('warning')->atLeast()->once();
});

test('feature registry stores and retrieves features across modules', function () {
    $provider = titanMakeDiscoveryProvider();

    $provider->testRegisterDeclaredModuleFeatures(
        titanMakeModuleStub('ModuleOne', [
            'capabilities' => ['capability.alpha', 'capability.beta'],
        ])
    );

    $provider->testRegisterDeclaredModuleFeatures(
        titanMakeModuleStub('ModuleTwo', [
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
    $provider = titanMakeDiscoveryProvider();

    $provider->testRegisterDeclaredModuleFeatures(
        titanMakeModuleStub('ModuleOne', ['capabilities' => ['feature.duplicate']])
    );
    $provider->testRegisterDeclaredModuleFeatures(
        titanMakeModuleStub('ModuleTwo', ['features' => ['feature.duplicate']])
    );

    /** @var FeatureRegistry $features */
    $features = app('titan.features');

    expect($features->owner('feature.duplicate'))->toBe('ModuleOne')
        ->and($features->warnings())->not->toBeEmpty();

    Log::shouldHaveReceived('warning')->atLeast()->once();
});

test('safe-boot failures appear in modules doctor output', function () {
    app()->instance('titan.module_boot_failures', [[
        'module' => 'BrokenModule',
        'provider' => 'Modules\\Broken\\Providers\\MissingProvider',
        'error' => 'Class not found',
    ]]);

    Artisan::call('modules:doctor', ['--skip-schema' => true]);
    $output = Artisan::output();

    expect($output)->toContain('Safe-boot provider failures detected')
        ->toContain('BrokenModule')
        ->toContain('Modules\\Broken\\Providers\\MissingProvider');
});
