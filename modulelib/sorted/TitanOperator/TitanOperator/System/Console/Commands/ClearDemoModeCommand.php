<?php

namespace App\Extensions\TitanOperator\System\Console\Commands;

use App\Extensions\TitanOperator\System\Models\TitanOperator;
use App\Helpers\Classes\Helper;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ClearDemoModeCommand extends Command
{
    protected $signature = 'app:clear-titan_operator-demo-mode';

    protected $description = 'Clear titan_operator demo mode';

    public function handle(): void
    {
        if (Helper::appIsNotDemo()) {
            return;
        }

        Log::info('Clear titan_operator demo mode new');

        TitanOperator::query()->where('is_demo', '=', 0)
            ->where('created_at', '<', now()->subMinutes(30))
            ->delete();
    }
}
