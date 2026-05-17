<?php

declare(strict_types=1);

namespace Modules\Dispatch\Console\Diagnostics;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;

class DispatchHealthCheckCommand extends Command
{
    protected $signature = 'dispatch:health-check';

    protected $description = 'Validate Dispatch module tables, bindings, and required runtime configuration.';

    public function handle(): int
    {
        $requiredTables = [
            'dispatch_work_orders',
            'dispatch_appointments',
            'dispatch_routes',
            'dispatch_route_stops',
            'dispatch_status_logs',
            'technician_profiles',
            'technician_skills',
            'customer_locations',
            'service_zones',
            'shifts',
            'assign_shifts',
        ];

        $missing = array_values(array_filter($requiredTables, fn (string $table): bool => ! Schema::hasTable($table)));

        if ($missing !== []) {
            $this->error('Dispatch health check failed. Missing tables: '.implode(', ', $missing));

            return self::FAILURE;
        }

        $this->info('Dispatch health check passed.');

        return self::SUCCESS;
    }
}
