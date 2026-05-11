<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class MakeSuperAdmin extends Command
{
    protected $signature = 'titan:make-super-admin
                            {--name=Super Admin : Display name}
                            {--email=admin@titan.pro : Email address}
                            {--password=password : Initial password (change after first login)}';

    protected $description = 'Create or promote a user to the super_admin role with verified email';

    public function handle(): int
    {
        $email = $this->option('email');

        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name'              => $this->option('name'),
                'password'          => Hash::make($this->option('password')),
                'email_verified_at' => now(),
            ]
        );

        // Ensure the email is verified
        if (! $user->hasVerifiedEmail()) {
            $user->email_verified_at = now();
            $user->save();
        }

        // Ensure the super_admin role exists
        $role = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);

        if (! $user->hasRole('super_admin')) {
            $user->assignRole($role);
            $this->info("User [{$email}] has been assigned the super_admin role.");
        } else {
            $this->info("User [{$email}] already has the super_admin role.");
        }

        $this->table(
            ['Field', 'Value'],
            [
                ['ID', $user->id],
                ['Name', $user->name],
                ['Email', $user->email],
                ['Email Verified', $user->email_verified_at ? 'Yes' : 'No'],
                ['Roles', $user->getRoleNames()->join(', ')],
                ['Panel URL', url('/titanpro')],
            ]
        );

        return self::SUCCESS;
    }
}
