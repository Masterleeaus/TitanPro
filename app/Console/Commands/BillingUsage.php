<?php

namespace App\Console\Commands;

use App\Models\Organization;
use App\Models\TitanUsageMeter;
use App\Services\PlanResolver;
use Illuminate\Console\Command;

class BillingUsage extends Command
{
    protected $signature = 'billing:usage
                            {organization : The organization ID or slug}';

    protected $description = 'Display current billing usage for an organization';

    public function __construct(private readonly PlanResolver $planResolver)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $identifier = $this->argument('organization');

        $org = Organization::where('id', $identifier)
            ->orWhere('slug', $identifier)
            ->first();

        if (! $org) {
            $this->error("Organization not found: {$identifier}");
            return self::FAILURE;
        }

        $plan    = $this->planResolver->resolve($org);
        $period  = now()->format('Y-m');
        $meters  = TitanUsageMeter::where('organization_id', $org->id)
            ->where('period', $period)
            ->get();
        $defined = array_keys(config('billing.meters', []));

        $this->info("Organization : {$org->name} (#{$org->id})");
        $this->info("Active plan  : {$plan}");
        $this->info("Period       : {$period}");
        $this->newLine();

        $rows = [];

        foreach ($defined as $meterKey) {
            $meter = $meters->firstWhere('meter_key', $meterKey);
            $count = $meter?->count ?? 0;
            $limit = $this->planResolver->limitFor($org, $meterKey);

            $limitLabel = $limit === null ? 'unlimited' : (string) $limit;
            $percent    = ($limit !== null && $limit > 0)
                ? round(($count / $limit) * 100, 1).'%'
                : '—';

            $rows[] = [$meterKey, $count, $limitLabel, $percent, $meter?->reset_at?->toDateTimeString() ?? '—'];
        }

        $this->table(
            ['Meter', 'Count', 'Limit', 'Used %', 'Resets At'],
            $rows
        );

        return self::SUCCESS;
    }
}
