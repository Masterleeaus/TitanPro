<?php

namespace Modules\TitanNexus\Console\Commands;

use Illuminate\Console\Command;

class ImportLegacyLeadPipelinesCommand extends Command
{
    protected $signature = 'titannexus:import-legacy-leads {--dry-run}';
    protected $description = 'Import legacy TitanLeadsBase pipeline/stage data into TitanNexus mappings.';

    public function handle(): int
    {
        $this->info('Legacy lead import adapter ready. Bind host tables before executing live import.');
        return self::SUCCESS;
    }
}
