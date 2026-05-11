
<?php
namespace App\Extensions\TitanPulse\System\Core\PulseEngine\Console\Commands\Concerns;
trait HasPulseGenerateQuery { public function cadenceWindow(string $cadence): array { return ['cadence' => $cadence, 'minute_before' => now()->subMinute()->format('H:i'), 'minute_after' => now()->addMinute()->format('H:i')]; } }
