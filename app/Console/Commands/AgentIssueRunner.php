<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AgentIssueRunner extends Command
{
    protected $signature   = 'titan:agent:run {--issue= : Specific issue number to process}';
    protected $description = 'Run a Claude Code agent on the oldest open GitHub issue';

    public function handle(): int
    {
        $script = base_path('scripts/agent-issue-runner.sh');

        if (! file_exists($script)) {
            $this->error("Script not found: $script");
            return self::FAILURE;
        }

        $issue = $this->option('issue');
        $cmd   = "bash " . escapeshellarg($script);

        if ($issue) {
            // Pass a specific issue number via environment variable
            $cmd = "FORCE_ISSUE=" . escapeshellarg($issue) . " $cmd";
        }

        $this->info("Starting agent issue runner...");
        passthru($cmd, $exitCode);

        return $exitCode === 0 ? self::SUCCESS : self::FAILURE;
    }
}
