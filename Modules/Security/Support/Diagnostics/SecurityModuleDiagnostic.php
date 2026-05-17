<?php

namespace Modules\Security\Support\Diagnostics;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Throwable;

class SecurityModuleDiagnostic
{
    public function report(): array
    {
        return [
            'module' => 'security',
            'status' => $this->status(),
            'tables' => $this->tables(),
            'routes' => $this->routes(),
            'config' => $this->config(),
            'recommendations' => $this->recommendations(),
        ];
    }

    public function status(): string
    {
        return in_array(false, $this->tables(), true) ? 'degraded' : 'ok';
    }

    public function tables(): array
    {
        $tables = [
            'tr_in_out_permit',
            'tr_workpermits',
            'tr_workpermit_files',
            'tr_access_card',
            'tr_access_card_items',
        ];

        $result = [];
        foreach ($tables as $table) {
            try {
                $result[$table] = Schema::hasTable($table);
            } catch (Throwable) {
                $result[$table] = false;
            }
        }

        return $result;
    }

    public function routes(): array
    {
        $expected = [
            'api.security.dashboard',
            'api.security.health',
            'api.security.status',
            'api.security.features',
            'api.security.permissions',
            'api.security.diagnostics',
        ];

        $routes = Route::getRoutes();
        $result = [];
        foreach ($expected as $name) {
            $result[$name] = (bool) $routes->getByName($name);
        }

        return $result;
    }

    public function config(): array
    {
        return [
            'features_loaded' => is_array(config('security_features.features')),
            'permissions_loaded' => is_array(config('security_permissions.permissions')),
            'workflows_loaded' => is_array(config('security_workflows.workflows')),
        ];
    }

    public function recommendations(): array
    {
        $recommendations = [];
        foreach ($this->tables() as $table => $exists) {
            if (! $exists) {
                $recommendations[] = "Run module migrations; missing table: {$table}";
            }
        }
        foreach ($this->routes() as $route => $exists) {
            if (! $exists) {
                $recommendations[] = "Refresh route cache; missing route: {$route}";
            }
        }

        return $recommendations;
    }
}
