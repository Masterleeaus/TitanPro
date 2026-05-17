<?php

namespace Modules\ExampleModule\Database\Seeders;

class ExampleModulePermissionSeeder
{
    public function run(): void { foreach(require __DIR__."/../../Config/permissions.php" as $permission){ \Spatie\Permission\Models\Permission::firstOrCreate(["name"=>$permission,"guard_name"=>"web"]); } }
}
