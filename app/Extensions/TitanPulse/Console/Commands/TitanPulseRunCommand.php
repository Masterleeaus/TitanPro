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
        $runSweeps = ! $this->option('signals-only');
        $runSignals = ! $this->option('sweeps-only');

        $runner = new \App\Extensions\TitanPulse\Automation\Workers\AutomationRunner();
        $result = $runner->run($limit, $teamId, $runSweeps, $runSignals);

        $this->info('TitanPulse run complete');

        if (is_array($result['signals'] ?? null)) {
            $this->line('Scanned: ' . ($result['signals']['scanned'] ?? 0));
            $this->line('Processed: ' . ($result['signals']['processed'] ?? 0));
            $this->line('Skipped: ' . ($result['signals']['skipped'] ?? 0));
            $this->line('Failed: ' . ($result['signals']['failed'] ?? 0));
        }

        if (is_array($result['sweeps'] ?? null)) {
            $this->line('Sweeps Ran: ' . ($result['sweeps']['ran'] ?? 0));
            $this->line('Sweep Skipped: ' . ($result['sweeps']['skipped'] ?? 0));
            $this->line('Sweep Failed: ' . ($result['sweeps']['failed'] ?? 0));
        }

        return self::SUCCESS;
    }
}
