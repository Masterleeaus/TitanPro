<?php

namespace App\Console\Commands;

use App\Models\TitanUsageMeter;
use Illuminate\Console\Command;

class BillingResetMeters extends Command
{
    protected $signature = 'billing:reset-meters
                            {--dry-run : Show what would be reset without making changes}';

    protected $description = 'Reset usage meters whose billing period has expired';

    public function handle(): int
    {
        $now = now();

        $query = TitanUsageMeter::where('reset_at', '<=', $now);

        $meters = $query->get();

        if ($meters->isEmpty()) {
            $this->info('No meters due for reset.');
            return self::SUCCESS;
        }

        $this->info("Found {$meters->count()} meter(s) due for reset.");

        foreach ($meters as $meter) {
            $this->line(
                "  → org={$meter->organization_id} meter={$meter->meter_key} "
                ."period={$meter->period} count={$meter->count}"
            );

            if (! $this->option('dry-run')) {
                // Always advance to the current billing period so that
                // meters lagging multiple periods are caught up in one pass.
                $meter->update([
                    'period'   => $now->format('Y-m'),
                    'count'    => 0,
                    'reset_at' => $now->copy()->startOfMonth()->addMonth(),
                ]);
            }
        }

        if ($this->option('dry-run')) {
            $this->warn('Dry-run mode — no changes were written.');
        } else {
            $this->info('Meters reset successfully.');
        }

        return self::SUCCESS;
    }
}
