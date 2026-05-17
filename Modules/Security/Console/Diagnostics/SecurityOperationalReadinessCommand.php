<?php

namespace Modules\Security\Console\Diagnostics;

use Illuminate\Console\Command;
use Modules\Security\Contracts\Services\OperationalReadinessServiceInterface;

class SecurityOperationalReadinessCommand extends Command
{
    protected $signature = 'security:readiness {--json : Output machine-readable JSON}';

    protected $description = 'Run production readiness checks for the Security module.';

    public function handle(OperationalReadinessServiceInterface $readiness): int
    {
        $report = $readiness->report();

        if ($this->option('json')) {
            $this->line(json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        } else {
            $this->info('Security module readiness: ' . strtoupper($report['status']));

            foreach ($report['checks'] as $name => $check) {
                $status = strtoupper($check['status'] ?? 'fail');
                $this->line(sprintf(' - %s: %s', $name, $status));
            }
        }

        return $report['status'] === 'ready' ? self::SUCCESS : self::FAILURE;
    }
}
