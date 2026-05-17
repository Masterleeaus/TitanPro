<?php

namespace App\Extensions\TitanCommand\System\JobManager\Console;

use Illuminate\Console\Command;








class JobManagerSelfTestCommand extends Command
{
    protected $signature = 'jobmanager:selftest {--queue}';
    protected $description = 'Quick smoke test for Job Manager config, DB, and optional queue dispatch';

    public function handle(): int
    {
        $ok = true;
        $this->info('[JobManager] Self test start');

        // Config check
        $auth = config('jobmanager.api_auth');
        $this->line(' - api_auth: '.var_export($auth, true));

        // DB table check
        $hasFailed = Schema::hasTable('jobmanager_failed_webhooks');
        $this->line(' - failed_webhooks table: '.($hasFailed ? 'ok' : 'missing'));

        if (!$hasFailed) { $ok = false; }

        if ($this->option('queue')) {
            $this->line(' - dispatching test queue job');
            WebhookDispatchJob::dispatch(['event'=>'SelfTest','ts'=>now()->toISOString()]);
        }

        $this->info($ok ? '[JobManager] Self test OK' : '[JobManager] Self test found issues');
        return $ok ? 0 : 1;
    }
}
