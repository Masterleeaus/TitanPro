<?php

namespace App\Extensions\TitanPulse\Console\Commands;

use Illuminate\Console\Command;

class TitanPulseRunCommand extends Command
{
    protected $signature = 'titan:pulse-run {--limit=200} {--team_id=} {--sweeps-only} {--signals-only}';
    protected $description = 'TitanPulse: consume tz_signals, apply automation rules, create suggestions/pending actions/analyses, and log runs.';

    public function handle(): int
    {
        $limit = (int) $this->option('limit');
        $teamId = $this->option('team_id');
        $teamId = $teamId !== null ? (int) $teamId : null;

        $runner = new \App\Extensions\TitanPulse\Automation\Workers\AutomationRunner();
        $result = $runner->run($limit, $teamId);

        $this->info('TitanPulse run complete');
        $this->line('Scanned: ' . $result['scanned']);
        $this->line('Processed: ' . $result['processed']);
        $this->line('Skipped: ' . $result['skipped']);
        $this->line('Failed: ' . $result['failed']);

        return self::SUCCESS;
    }
}
