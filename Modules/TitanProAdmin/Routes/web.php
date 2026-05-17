<?php

use Illuminate\Support\Facades\Route;
use Modules\TitanProAdmin\Actions\DisableModuleAction;
use Modules\TitanProAdmin\Actions\EnableModuleAction;
use Modules\TitanProAdmin\Actions\SuspendTenantAction;
use Modules\TitanProAdmin\Services\PlatformHealthService;
use Modules\TitanProAdmin\Services\TenantService;

Route::prefix(config('titanproadmin.routes.web_prefix', 'admin/titanpro-admin'))
    ->middleware(['web', 'auth:super_admin', 'role:super_admin'])
    ->name('titanproadmin.web.')
    ->group(function (): void {
        Route::get('/health', function (PlatformHealthService $service) {
            return response()->json($service->status());
        })->name('health');

        Route::get('/tenants/{tenant}/config', function (int $tenant, TenantService $service) {
            return response()->json(['data' => $service->getTenantConfig($tenant)]);
        })->name('tenants.config.show');

        Route::put('/tenants/{tenant}/config', function (int $tenant, TenantService $service) {
            return response()->json([
                'data' => $service->updateTenantConfig(
                    tenantId: $tenant,
                    config: request()->input('config', []),
                    actorId: request()->user('super_admin')?->getAuthIdentifier()
                ),
            ]);
        })->name('tenants.config.update');

        Route::post('/tenants/{tenant}/suspend', function (int $tenant, SuspendTenantAction $action) {
            return response()->json([
                'ok' => true,
                'tenant_id' => $action->execute(
                    tenantId: $tenant,
                    actorId: request()->user('super_admin')?->getAuthIdentifier()
                )?->getKey(),
            ]);
        })->name('tenants.suspend');

        Route::post('/tenants/{tenant}/modules/{module}/enable', function (int $tenant, string $module, EnableModuleAction $action) {
            return response()->json([
                'ok' => true,
                'enabled_modules' => $action->execute(
                    tenantId: $tenant,
                    moduleName: $module,
                    actorId: request()->user('super_admin')?->getAuthIdentifier()
                ),
            ]);
        })->name('modules.enable');

        Route::post('/tenants/{tenant}/modules/{module}/disable', function (int $tenant, string $module, DisableModuleAction $action) {
            return response()->json([
                'ok' => true,
                'enabled_modules' => $action->execute(
                    tenantId: $tenant,
                    moduleName: $module,
                    actorId: request()->user('super_admin')?->getAuthIdentifier()
                ),
            ]);
        })->name('modules.disable');
    });
