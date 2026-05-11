<?php
namespace App\Extensions\TitanPulse\System\Services\Conditions\Payload;
use Illuminate\Support\Arr;
class PayloadAccessor { public function get(array $payload, string $path, mixed $default=null): mixed { return Arr::get($payload, $path, $default); } }
