<?php

declare(strict_types=1);

namespace App\Extensions\TitanPulse\System\Console\Commands;

use App\Extensions\TitanPulse\System\Services\PulseEngine;
use Illuminate\Console\Command;

class PulseRunCommand extends Command
{
    protected $signature = 'titan:pulse-run {--limit=200} {--signals-only} {--sweeps-only} {--team_id=} {--pack=}';

    protected $description = 'Consume tz_signals and run Titan Pulse automation rules';

    public function handle(PulseEngine $engine): int
    {
        $result = $engine->run([
            'limit' => (int) $this->option('limit'),
            'signals_only' => (bool) $this->option('signals-only'),
            'sweeps_only' => (bool) $this->option('sweeps-only'),
            'team_id' => $this->option('team_id') ? (int) $this->option('team_id') : null,
            'pack' => $this->option('pack') ? (string) $this->option('pack') : '',
        ]);

        $this->info(sprintf(
            'Pulse complete. Signals: %d, sweeps: %d, runs: %d, suggestions: %d, actions: %d, analyses: %d.',
            $result['signals_processed'],
            $result['sweeps_processed'],
            $result['runs_created'],
            $result['suggestions_created'],
            $result['pending_actions_created'],
            $result['analyses_created'],
        ));

        return self::SUCCESS;
    }
}
