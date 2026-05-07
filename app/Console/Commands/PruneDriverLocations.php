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

        $backfilled = 0;

        DriverLocation::query()
            ->whereNull('organization_id')
            ->select('id', 'user_id')
            ->orderBy('id')
            ->chunkById(100, function ($locations) use (&$backfilled): void {
                $organizationIds = DB::table('users')
                    ->whereIn('id', $locations->pluck('user_id')->filter()->unique())
                    ->pluck('organization_id', 'id');

                foreach ($locations as $location) {
                    $organizationId = $organizationIds[$location->user_id] ?? null;

                    if ($organizationId === null) {
                        continue;
                    }

                    $backfilled += DriverLocation::query()
                        ->whereKey($location->id)
                        ->update(['organization_id' => $organizationId]);
                }
            });

        $deleted = DriverLocation::query()
            ->where('recorded_at', '<', now()->subDays($days))
            ->delete();

        $this->info("Backfilled {$backfilled} driver location record(s) with organization_id.");
        $this->info("Pruned {$deleted} driver location record(s) older than {$days} day(s).");

        return self::SUCCESS;
    }
}
