<?php

declare(strict_types=1);

namespace Modules\Budgeting\Jobs\Queued;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Budgeting\Models\ForecastScenario;

class GenerateForecastScenarioJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 2;

    public int $timeout = 300;

    public function __construct(public readonly ForecastScenario $scenario) {}

    public function handle(): void
    {
        $this->scenario->update(['status' => 'running']);

        // AI-powered forecast generation — integrate with forecasting model
        // On success: update status='ready', scenario_data, generated_at

        $this->scenario->update([
            'status' => 'ready',
            'generated_at' => now(),
        ]);
    }
}
