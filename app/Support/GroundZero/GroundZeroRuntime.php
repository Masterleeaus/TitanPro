<?php

namespace App\Support\GroundZero;

use Illuminate\Support\Facades\DB;

class GroundZeroRuntime
{
    public function status(): array
    {
        return [
            'titanzero' => class_exists(\Modules\TitanZero\Providers\TitanZeroServiceProvider::class),
            'titancore' => class_exists(\Modules\TitanCore\Providers\TitanCoreServiceProvider::class),
            'zero_gateway' => class_exists(\Modules\TitanZero\Services\ZeroGateway::class),
            'core_router' => class_exists(\Modules\TitanCore\Services\TitanCoreRouter::class),
        ];
    }

    public function timeline(): array
    {
        $events = [];

        foreach ([
            'jobs' => 'Job activity',
            'invoices' => 'Invoice activity',
            'customers' => 'Customer activity',
            'users' => 'Team activity',
        ] as $table => $label) {
            try {
                if (DB::getSchemaBuilder()->hasTable($table)) {
                    $count = DB::table($table)->count();
                    $events[] = [
                        'title' => $label,
                        'description' => number_format($count).' records available for GroundZero orchestration.',
                        'time' => 'Live',
                    ];
                }
            } catch (\Throwable) {
                // Keep workspace resilient when optional tables differ by tenant install.
            }
        }

        return $events ?: [[
            'title' => 'GroundZero online',
            'description' => 'Conversational business workspace is ready. Connect TitanCore provider keys to enable live AI execution.',
            'time' => 'Now',
        ]];
    }
}
