<?php

use Illuminate\Routing\RouteCollection;
use Nwidart\Modules\Facades\Module;
use Modules\CRMCore\Providers\ModuleBootServiceProvider;
use Modules\CRMCore\Providers\RouteServiceProvider;
use Spatie\Permission\Models\Permission;

function withFreshRouter(callable $callback): void
{
    $router = app('router');
    $originalRoutes = $router->getRoutes();
    $router->setRoutes(new RouteCollection());

    try {
        $callback($router);
    } finally {
        $router->setRoutes($originalRoutes);
    }
}

test('crmcore route surfaces are auto-registered from manifest with expected middleware and prefixes', function () {
    Module::shouldReceive('has')->with('CRMCore')->andReturn(true);
    Module::shouldReceive('isEnabled')->with('CRMCore')->andReturn(true);

    withFreshRouter(function ($router): void {
        (new RouteServiceProvider(app()))->map();

        $web = $router->getRoutes()->getByName('crmcore.web.health');
        expect($web)->not->toBeNull();
        expect($web->uri())->toBe('crmcore/health');
        expect($web->gatherMiddleware())->toContain('web', 'auth');

        $api = $router->getRoutes()->getByName('crmcore.api.health');
        expect($api)->not->toBeNull();
        expect($api->uri())->toBe('api/crmcore/health');
        expect($api->gatherMiddleware())->toContain('api', 'auth:sanctum');

        $internal = $router->getRoutes()->getByName('crmcore.internal.health');
        expect($internal)->not->toBeNull();
        expect($internal->uri())->toBe('internal/crmcore/health');
        expect($internal->gatherMiddleware())->toContain('web', 'auth', 'module.admin');

        $tenant = $router->getRoutes()->getByName('crmcore.tenant.health');
        expect($tenant)->not->toBeNull();
        expect($tenant->uri())->toBe('tenant/crmcore/health');
        expect($tenant->gatherMiddleware())->toContain('web', 'auth');
    });
});

test('disabled crmcore module does not register module routes', function () {
    Module::shouldReceive('has')->with('CRMCore')->andReturn(true);
    Module::shouldReceive('isEnabled')->with('CRMCore')->andReturn(false);

    withFreshRouter(function ($router): void {
        (new RouteServiceProvider(app()))->map();

        expect($router->getRoutes()->count())->toBe(0);
    });
});

test('crmcore navigation manifest groups are loaded into navigation registry config', function () {
    Module::shouldReceive('has')->with('CRMCore')->andReturn(true);
    Module::shouldReceive('isEnabled')->with('CRMCore')->andReturn(true);

    config(['titan.navigation.manifests' => []]);

    (new ModuleBootServiceProvider(app()))->boot();

    $registry = config('titan.navigation.manifests', []);

    expect($registry)->toHaveKey('crmcore');
    expect($registry['crmcore'][0]['label'])->toBe('CRM Core');
    expect($registry['crmcore'][1]['label'])->toBe('CRM Automation');
});

test('modules permissions sync command creates crmcore permissions idempotently', function () {
    $this->artisan('modules:permissions-sync', ['--module' => ['CRMCore']])->assertExitCode(0);

    expect(Permission::where('name', 'crmcore.view')->where('guard_name', 'web')->exists())->toBeTrue();
    $firstCount = Permission::where('name', 'like', 'crmcore.%')->count();
    expect($firstCount)->toBeGreaterThan(0);

    $this->artisan('modules:permissions-sync', ['--module' => ['CRMCore']])->assertExitCode(0);

    $secondCount = Permission::where('name', 'like', 'crmcore.%')->count();
    expect($secondCount)->toBe($firstCount);
});

test('modules permissions sync skips disabled modules', function () {
    Module::shouldReceive('has')->with('CRMCore')->andReturn(true);
    Module::shouldReceive('isEnabled')->with('CRMCore')->andReturn(false);

    $this->artisan('modules:permissions-sync', ['--module' => ['CRMCore']])->assertExitCode(0);

    expect(Permission::where('name', 'like', 'crmcore.%')->count())->toBe(0);
});
