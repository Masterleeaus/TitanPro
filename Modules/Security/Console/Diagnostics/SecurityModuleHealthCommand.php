<?php

namespace Modules\Security\Console\Diagnostics;

use Illuminate\Console\Command;
use Modules\Security\Contracts\Services\SecurityModuleServiceInterface;

class SecurityModuleHealthCommand extends Command
{
    protected $signature = 'security:health {--json : Output machine-readable JSON}';

    protected $description = 'Run Security module health diagnostics.';

    public function handle(SecurityModuleServiceInterface $securityModule): int
    {
        $health = $securityModule->health();

        if ($this->option('json')) {
            $this->line(json_encode($health, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            return $health['status'] === 'ok' ? self::SUCCESS : self::FAILURE;
        }

        $this->info('Security module status: ' . strtoupper($health['status']));
        foreach ($health['tables'] as $table => $exists) {
            $this->line(sprintf(' - %s: %s', $table, $exists ? 'ok' : 'missing'));
        }

        return $health['status'] === 'ok' ? self::SUCCESS : self::FAILURE;
    }
}
