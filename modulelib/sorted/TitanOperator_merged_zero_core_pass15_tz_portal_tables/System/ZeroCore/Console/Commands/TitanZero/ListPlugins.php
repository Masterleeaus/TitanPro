<?php

namespace App\Extensions\TitanOperator\System\ZeroCore\Console\Commands\TitanZero;

use App\Extensions\TitanOperator\System\ZeroCore\Services\TitanZero\Plugins\Registry\PluginRegistry;
use Illuminate\Console\Command;

/**
 * TitanZeroChat — List Plugins Command
 *
 * Usage: php artisan tzc:list-plugins
 *
 * Outputs a table of all registered plugins with their enabled/disabled status,
 * migration path, and whether any plugins failed to boot.
 */
class ListPlugins extends Command
{
    protected $signature   = 'tzc:list-plugins {--failed : Show only plugins that failed to boot} {--json : Output the plugin manifest as JSON}';
    protected $description = 'List all registered TitanZeroChat plugins and their status';

    public function handle(PluginRegistry $registry): int
    {
        $plugins = $registry->all();
        $failed  = $registry->failed();

        if ($this->option('json')) {
            $this->line(json_encode($registry->manifest(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

            return self::SUCCESS;
        }

        if (empty($plugins)) {
            $this->warn('No plugins registered.');
            return self::SUCCESS;
        }

        if ($this->option('failed')) {
            if (empty($failed)) {
                $this->info('✓ All plugins booted successfully.');
                return self::SUCCESS;
            }
            $this->error('Failed plugins:');
            foreach ($failed as $id) {
                $this->line("  ✗ {$id}");
            }
            return self::FAILURE;
        }

        $rows = [];
        foreach ($plugins as $plugin) {
            $isFailed  = in_array($plugin->id(), $failed);
            $status    = $isFailed ? '<fg=red>FAILED</>' : ($plugin->enabled() ? '<fg=green>ENABLED</>' : '<fg=yellow>DISABLED</>');
            $hasMigrations = $plugin->migrations() ? '✓' : '—';
            $commandCount  = count($plugin->commands());

            $rows[] = [
                $plugin->id(),
                $plugin->label(),
                $status,
                $hasMigrations,
                $commandCount > 0 ? $commandCount : '—',
            ];
        }

        $this->table(
            ['ID', 'Label', 'Status', 'Migrations', 'Commands'],
            $rows
        );

        $this->line('');
        $this->line('Total: ' . count($plugins) . ' plugins | Enabled: ' . count($registry->enabledPlugins()) . ' | Disabled: ' . count($registry->disabledPlugins()));

        if (!empty($failed)) {
            $this->warn('⚠  ' . count($failed) . ' plugin(s) failed to boot. Run --failed for details.');
        }

        return self::SUCCESS;
    }
}
