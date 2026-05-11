<?php

namespace Modules\JobManager\Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;


namespace ModulesJobManagerDatabaseSeeders;


namespace Modules\JobManager\Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class JobManagerPermissionSeeder extends Seeder
{
    public function run(): void
    {
        $perms = [
            'jobmanager.view','jobmanager.create','jobmanager.update','jobmanager.delete',
            'jobmanager.types.manage','jobmanager.requests.manage',
            'jobmanager.appointments.manage',
            'jobmanager.tasks.manage',
            'jobmanager.parts.manage',
        ];

        foreach ($perms as $p) { Permission::firstOrCreate(['name' => $p]); }

        $admin = Role::firstOrCreate(['name' => 'admin']);
        $tech  = Role::firstOrCreate(['name' => 'technician']);
        $viewer= Role::firstOrCreate(['name' => 'viewer']);

        $admin->syncPermissions(Permission::all());
        $tech->syncPermissions([
            'jobmanager.view','jobmanager.update',
            'jobmanager.appointments.manage',
            'jobmanager.tasks.manage','jobmanager.parts.manage',
        ]);
        $viewer->syncPermissions(['jobmanager.view']);

        if ($user = User::first()) { $user->assignRole($admin); }
    }
}
