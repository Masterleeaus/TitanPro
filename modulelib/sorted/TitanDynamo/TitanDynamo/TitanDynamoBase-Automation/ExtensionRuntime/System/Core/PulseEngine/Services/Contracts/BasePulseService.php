
<?php
namespace App\Extensions\TitanPulse\System\Core\PulseEngine\Services\Contracts;
abstract class BasePulseService { abstract public function handle(array $payload = []): array; }
