<?php

namespace App\Console\Commands;

use App\Models\DriverLocation;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PruneDriverLocations extends Command
{
    protected $signature = 'driver-locations:prune
                            {--days=7 : Delete location records older than this many days}';

    protected $description = 'Remove driver location records older than the retention period';

    public function handle(): int
    {
        $days = (int) $this->option('days');

        $backfilled = DriverLocation::query()
            ->whereNull('organization_id')
            ->update([
                'organization_id' => DB::raw('(select organization_id from users where users.id = driver_locations.user_id)'),
            ]);

        $deleted = DriverLocation::query()
            ->where('recorded_at', '<', now()->subDays($days))
            ->delete();

        $this->info("Backfilled {$backfilled} driver location record(s) with organization_id.");
        $this->info("Pruned {$deleted} driver location record(s) older than {$days} day(s).");

        return self::SUCCESS;
    }
}
