<?php

namespace Modules\Security\Console\Diagnostics;

use Illuminate\Console\Command;
use Modules\Security\Support\Diagnostics\SecurityStructureAudit;

class SecurityStructureAuditCommand extends Command
{
    protected $signature = 'security:structure-audit {--json : Output JSON only}';

    protected $description = 'Audit the Security module against the enterprise blueprint scaffold.';

    public function handle(SecurityStructureAudit $audit): int
    {
        $result = $audit->scan();

        if ($this->option('json')) {
            $this->line(json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            return $result['status'] === 'ok' ? self::SUCCESS : self::FAILURE;
        }

        $this->info('Security blueprint structure audit: ' . strtoupper($result['status']));
        $this->line('Coverage: ' . $result['coverage_percent'] . '%');
        $this->line('Present directories: ' . $result['present_directories'] . '/' . $result['expected_directories']);

        foreach ($result['missing_directories'] as $directory) {
            $this->warn('Missing: ' . $directory);
        }

        return $result['status'] === 'ok' ? self::SUCCESS : self::FAILURE;
    }
}
