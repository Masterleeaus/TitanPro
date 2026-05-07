<?php

namespace Modules\CRMCore\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;
use Nwidart\Modules\Facades\Module;

class RouteServiceProvider extends ServiceProvider
{
    public function map(): void
    {
        if ($this->moduleIsDisabled()) {
            return;
        }

        foreach ($this->routeSurfaces() as $surface) {
            $path = __DIR__ . '/../Routes/' . $surface . '.php';

            if (! file_exists($path)) {
                continue;
            }

            $route = match ($surface) {
                'api' => Route::middleware(['api', 'auth:sanctum'])
                    ->prefix('api/crmcore')
                    ->name('crmcore.api.'),
                'internal' => Route::middleware(['web', 'auth', 'module.admin'])
                    ->prefix('internal/crmcore')
                    ->name('crmcore.internal.'),
                'tenant' => Route::middleware(['web', 'auth'])
                    ->prefix('tenant/crmcore')
                    ->name('crmcore.tenant.'),
                default => Route::middleware(['web', 'auth'])
                    ->prefix('crmcore')
                    ->name('crmcore.web.'),
            };

            $route->group($path);
        }
    }

    /**
     * @return array<int, string>
     */
    private function routeSurfaces(): array
    {
        $manifestPath = __DIR__ . '/../manifests/routes.manifest.json';
        if (! file_exists($manifestPath)) {
            return ['web', 'api', 'internal', 'tenant'];
        }

        $decoded = json_decode((string) file_get_contents($manifestPath), true);
        $routes = is_array($decoded['routes'] ?? null) ? $decoded['routes'] : [];

        return array_values(array_filter($routes, static fn ($route) => is_string($route) && $route !== ''));
    }

    private function moduleIsDisabled(): bool
    {
        if (! class_exists(Module::class) || ! app()->bound('modules')) {
            return false;
        }

        if (! Module::has('CRMCore')) {
            return false;
        }

        return ! Module::isEnabled('CRMCore');
    }
}
