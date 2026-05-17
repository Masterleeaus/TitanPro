<?php

namespace Modules\Security\Console\Repair;

use Illuminate\Console\Command;
use Modules\Security\Support\Diagnostics\SecurityModuleDiagnostic;

class SecurityModuleRepairCommand extends Command
{
    protected $signature = 'security:repair {--dry-run : Only show detected repair actions}';

    protected $description = 'Inspect repairable Security module wiring issues and show recovery actions.';

    public function handle(SecurityModuleDiagnostic $diagnostic): int
    {
        $report = $diagnostic->report();
        $this->info('Security module repair scan: ' . $report['status']);

        foreach ($report['recommendations'] as $recommendation) {
            $this->line('- ' . $recommendation);
        }

        if ($this->option('dry-run')) {
            $this->comment('Dry run only. No changes were applied.');
            return self::SUCCESS;
        }

        $this->comment('Automatic destructive repair is intentionally disabled; run the listed framework commands explicitly.');

        return self::SUCCESS;
    }
}
