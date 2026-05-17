<?php

namespace Modules\JobManager\Console;

use Illuminate\Console\Command;


namespace Modules\JobManager\Console;

<?php

namespace ModulesJobManagerConsole;


namespace Modules\JobManager\Console;

use Illuminate\Console\Command;
class JobManagerUninstallCommand extends Command
{
    protected $signature = 'jobmanager:uninstall {--dry-run} {--force} {--drop-tables}';
    protected $description = 'Safely uninstall Job Manager: revoke permissions, remove seeds; optionally drop tables.';

    public function handle(): int
    {
        $dry = $this->option('dry-run') || !$this->option('force');

        $this->info('[JobManager] Uninstall started ' . ($dry ? '(dry-run)' : '(EXECUTE)'));

        // 1) Permissions cleanup
        $permTable = $this->guessPermissionsTable();
        $perms = ['jobmanager.view','jobmanager.create','jobmanager.update','jobmanager.delete','jobmanager.settings'];

        if ($permTable && Schema::hasTable($permTable)) {
            foreach ($perms as $perm) {
                $this->line(" - remove permission: {$perm}");
                if (!$dry) {
                    DB::table($permTable)->where('name', $perm)->delete();
                }
            }
        } else {
            $this->warn(" - permissions table not found; skipping (looked for {$permTable})");
        }

        // 2) Demo/seed cleanup (best-effort)
        $candidates = [
            'work_orders', 'wo_requests', 'wo_service_tasks', 'wo_service_parts', 'wo_types',
            'wo_service_appointments', 'work_orders_settings', 'estimation_service_parts'
        ];
        foreach ($candidates as $t) {
            if (Schema::hasTable($t)) {
                $this->line(" - purge demo rows from {$t} (non-destructive)");
                if (!$dry) {
                    try {
                        DB::table($t)->where('is_demo', 1)->delete();
                    } catch (\Throwable $e) {
                        // ignore
                    }
                }
            }
        }

        // 3) Optional: drop tables
        if ($this->option('drop-tables')) {
            foreach ($candidates as $t) {
                if (Schema::hasTable($t)) {
                    $this->line(" - DROP TABLE {$t}");
                    if (!$dry) {
                        Schema::drop($t);
                    }
                }
            }
        }

        $this->info('[JobManager] Uninstall complete.');
        $this->info('Note: run with --force to execute; --drop-tables to remove schema.');
        return 0;
    }

    protected function guessPermissionsTable(): ?string
    {
        $guesses = ['permissions', 'role_permissions', 'module_permissions'];
        foreach ($guesses as $g) {
            if (Schema::hasTable($g)) return $g;
        }
        return null;
    }
}
