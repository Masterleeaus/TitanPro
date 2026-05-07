<?php

namespace Modules\CRMCore\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Nwidart\Modules\Facades\Module;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class ModulesPermissionsSyncCommand extends Command
{
    protected $signature = 'modules:permissions-sync {--module=* : Limit sync to specific module name(s)}';

    protected $description = 'Sync permissions declared in module manifests into Spatie permissions.';

    public function handle(): int
    {
        $filter = array_filter((array) $this->option('module'));
        $created = 0;
        $scanned = 0;

        foreach (File::directories(base_path('Modules')) as $moduleDir) {
            $dirName = basename($moduleDir);
            $manifestName = $this->manifestName($moduleDir) ?? $dirName;

            if ($filter !== [] && ! in_array($manifestName, $filter, true) && ! in_array($dirName, $filter, true)) {
                continue;
            }

            if ($this->moduleIsDisabled($manifestName, $dirName)) {
                continue;
            }

            $permissionsPath = $moduleDir . '/manifests/permissions.manifest.json';
            if (! file_exists($permissionsPath)) {
                continue;
            }

            $raw = file_get_contents($permissionsPath);
            if ($raw === false) {
                continue;
            }

            $decoded = json_decode($raw, true);
            $decoded = is_array($decoded) ? $decoded : [];
            $permissions = is_array($decoded['permissions'] ?? null) ? $decoded['permissions'] : [];

            $scanned++;

            foreach ($permissions as $name) {
                if (! is_string($name) || $name === '') {
                    continue;
                }

                $permission = Permission::firstOrCreate([
                    'name' => $name,
                    'guard_name' => 'web',
                ]);

                if ($permission->wasRecentlyCreated) {
                    $created++;
                }
            }
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $this->info("Permissions sync complete. Modules scanned: {$scanned}. Permissions created: {$created}.");

        return self::SUCCESS;
    }

    private function manifestName(string $moduleDir): ?string
    {
        $moduleJson = $moduleDir . '/module.json';
        if (! file_exists($moduleJson)) {
            return null;
        }

        $raw = file_get_contents($moduleJson);
        if ($raw === false) {
            return null;
        }

        $decoded = json_decode($raw, true);
        $decoded = is_array($decoded) ? $decoded : [];

        return is_string($decoded['name'] ?? null) ? $decoded['name'] : null;
    }

    private function moduleIsDisabled(string $manifestName, string $dirName): bool
    {
        if (! class_exists(Module::class) || ! app()->bound('modules')) {
            return false;
        }

        foreach (array_unique([$manifestName, $dirName]) as $candidate) {
            if ($candidate === '') {
                continue;
            }

            if (Module::has($candidate)) {
                return ! Module::isEnabled($candidate);
            }
        }

        return false;
    }
}
