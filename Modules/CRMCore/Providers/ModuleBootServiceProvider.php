<?php

namespace Modules\CRMCore\Providers;

use Illuminate\Support\ServiceProvider;
use Nwidart\Modules\Facades\Module;

class ModuleBootServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if ($this->moduleIsDisabled()) {
            return;
        }

        $manifestPath = __DIR__ . '/../manifests/navigation.manifest.json';
        if (! file_exists($manifestPath)) {
            return;
        }

        $decoded = json_decode((string) file_get_contents($manifestPath), true);
        $groups = is_array($decoded['groups'] ?? null) ? $decoded['groups'] : [];

        if ($groups === []) {
            return;
        }

        $registry = config('titan.navigation.manifests', []);
        if (! is_array($registry)) {
            $registry = [];
        }

        $registry['crmcore'] = $groups;

        config(['titan.navigation.manifests' => $registry]);
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
