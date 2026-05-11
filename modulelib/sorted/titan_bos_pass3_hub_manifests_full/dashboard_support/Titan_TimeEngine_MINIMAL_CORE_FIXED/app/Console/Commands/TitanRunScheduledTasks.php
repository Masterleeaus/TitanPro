<?php

namespace App\Console\Commands;

use App\Services\TitanScheduleEngine\ScheduleEngine;
use Illuminate\Console\Command;

class TitanRunScheduledTasks extends Command
{
    protected $signature = 'titan:schedule-run {--limit=50 : Maximum number of due tasks to execute}';

    protected $description = 'Run due tasks for the Titan Schedule Engine';

    public function handle(ScheduleEngine $scheduleEngine): int
    {
        $results = $scheduleEngine->runDueTasks((int) $this->option('limit'));
        $this->info('Titan Schedule Engine processed ' . count($results) . ' task(s).');

        foreach ($results as $index => $result) {
            $this->line(sprintf('%d. %s', $index + 1, json_encode($result)));
        }

        return self::SUCCESS;
    }
}
