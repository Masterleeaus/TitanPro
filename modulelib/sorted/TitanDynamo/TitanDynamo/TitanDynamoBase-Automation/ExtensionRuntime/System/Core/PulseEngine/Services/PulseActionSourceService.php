
<?php

namespace App\Extensions\TitanPulse\System\Core\PulseEngine\Services;

use Illuminate\Support\Fluent;

class PulseActionSourceService
{
    public static function channels(): array
    {
        return [
            'command_feed' => new Fluent(['id' => 'command_feed', 'name' => 'Command Feed']),
            'hive_feed' => new Fluent(['id' => 'hive_feed', 'name' => 'Hive Feed']),
            'duality_context' => new Fluent(['id' => 'duality_context', 'name' => 'Duality Context']),
        ];
    }

    public static function find(string $id): ?Fluent
    {
        return self::channels()[$id] ?? null;
    }
}
