<?php

namespace App\Extensions\TitanCommand\System\Console\Commands;

use App\Extensions\TitanCommand\System\Database\Seeders\TitanCommandDemoSeeder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Console\Command\Command as CommandAlias;

class SeedDemoDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'titancommand:seed-demo-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed demo agents and posts for Titan Command Agent extension';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Starting Titan Command Agent demo data seeding...');
        $this->newLine();

        Log::info('titancommand:seed-demo-data started');

        $seeder = new TitanCommandDemoSeeder;
        $seeder->setCommand($this);
        $seeder->run();

        $this->newLine();
        $this->info('✅ Demo data seeding completed!');
        Log::info('titancommand:seed-demo-data finished');

        return CommandAlias::SUCCESS;
    }
}
