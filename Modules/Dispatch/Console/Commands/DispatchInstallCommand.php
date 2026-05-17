<?php

declare(strict_types=1);

namespace Modules\Dispatch\Console\Commands;

use Illuminate\Console\Command;
use Modules\Dispatch\Database\Seeders\DispatchDemoSeeder;

class DispatchInstallCommand extends Command
{
    protected $signature = 'dispatch:install {--seed : Seed starter dispatch data}';
    protected $description = 'Install and verify the Dispatch module database and starter records.';

    public function handle(): int
    {
        $this->call('migrate', ['--path' => 'Modules/Dispatch/Database/Migrations']);

        if ($this->option('seed')) {
            $this->call(DispatchDemoSeeder::class);
        }

        $this->info('Dispatch module install complete.');

        return self::SUCCESS;
    }
}
