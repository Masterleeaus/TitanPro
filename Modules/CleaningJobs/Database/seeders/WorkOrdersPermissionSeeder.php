<?php

namespace Modules\CleaningJobs\Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class WorkOrdersPermissionSeeder extends Seeder
{
    public function run(): void
    {
        $perms = [
            'cleaningjobs.view','cleaningjobs.create','cleaningjobs.update','cleaningjobs.delete',
            'cleaningjobs.types.manage','cleaningjobs.requests.manage',
            'cleaningjobs.appointments.manage',
            'cleaningjobs.tasks.manage',
            'cleaningjobs.parts.manage',
        ];

        foreach ($perms as $p) { Permission::firstOrCreate(['name' => $p]); }

        $admin = Role::firstOrCreate(['name' => 'admin']);
        $tech  = Role::firstOrCreate(['name' => 'technician']);
        $viewer= Role::firstOrCreate(['name' => 'viewer']);

        $admin->syncPermissions(Permission::all());
        $tech->syncPermissions([
            'cleaningjobs.view','cleaningjobs.update',
            'cleaningjobs.appointments.manage',
            'cleaningjobs.tasks.manage','cleaningjobs.parts.manage',
        ]);
        $viewer->syncPermissions(['cleaningjobs.view']);

        if ($user = User::first()) { $user->assignRole($admin); }
    }
}
