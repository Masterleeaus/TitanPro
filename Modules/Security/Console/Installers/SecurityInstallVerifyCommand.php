<?php

namespace Modules\Security\Console\Installers;

use Illuminate\Console\Command;
use Modules\Security\Contracts\Services\OperationalReadinessServiceInterface;

class SecurityInstallVerifyCommand extends Command
{
    protected $signature = 'security:install-verify {--json : Output machine-readable JSON}';

    protected $description = 'Verify Security module installation readiness after deployment or upgrade.';

    public function handle(OperationalReadinessServiceInterface $readiness): int
    {
        $report = $readiness->report();

        if ($this->option('json')) {
            $this->line(json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        } else {
            $this->info('Install verification: ' . strtoupper($report['status']));
            foreach ($report['failed'] as $failed) {
                $this->warn('Failed check: ' . $failed);
            }
        }

        return $report['status'] === 'ready' ? self::SUCCESS : self::FAILURE;
    }
}
