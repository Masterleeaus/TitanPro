<?php

namespace App\Console\Commands;

use App\Support\ThemePolicyManager;
use Illuminate\Console\Command;

class ThemePolicyCommand extends Command
{
    protected $signature = 'theme:policy
        {action : show|role|tenant|user}
        {key? : Role, tenant, or user id}
        {--theme= : Theme slug}
        {--preset= : Preset slug}
        {--json : Output JSON}';

    protected $description = 'Manage role/tenant/user theme policy assignments.';

    public function handle(): int
    {
        $action = (string) $this->argument('action');

        if ($action === 'show') {
            $data = ThemePolicyManager::diagnostics();
            $this->line($this->option('json') ? json_encode($data, JSON_PRETTY_PRINT) : print_r($data, true));
            return self::SUCCESS;
        }

        $key = (string) $this->argument('key');

        if ($key === '') {
            $this->error('A key is required.');
            return self::FAILURE;
        }

        match ($action) {
            'role' => ThemePolicyManager::setRole($key, $this->option('theme'), $this->option('preset')),
            'tenant' => ThemePolicyManager::setTenant($key, $this->option('theme'), $this->option('preset')),
            'user' => ThemePolicyManager::setUser($key, $this->option('theme'), $this->option('preset')),
            default => null,
        };

        $this->info("Theme policy updated for {$action}: {$key}");
        return self::SUCCESS;
    }
}
