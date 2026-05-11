
<?php
namespace App\Extensions\TitanPulse\System\Core\PulseEngine\Console\Commands\Concerns;
trait HasPulseDynamicHandle { public function dynamicHandle(string $cadence): void { $this->info('Imported cadence handler: ' . $cadence); } }
