<?php

namespace Modules\ZeroPayModule\Database\Seeders;

use Spatie\Permission\Models\Permission;

class ZeroPayModulePermissionSeeder
{
    public function run(): void
    {
        foreach (require __DIR__.'/../../Config/permissions.php' as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }
    }
}
