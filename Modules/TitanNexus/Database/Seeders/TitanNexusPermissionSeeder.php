<?php

namespace Modules\TitanNexus\Database\Seeders;

class TitanNexusPermissionSeeder
{
    public function run(): void { foreach(require __DIR__."/../../Config/permissions.php" as $permission){ \Spatie\Permission\Models\Permission::firstOrCreate(["name"=>$permission,"guard_name"=>"web"]); } }
}
