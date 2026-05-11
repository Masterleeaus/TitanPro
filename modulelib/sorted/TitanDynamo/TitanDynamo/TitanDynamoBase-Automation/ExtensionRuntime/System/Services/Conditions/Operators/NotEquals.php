<?php namespace App\Extensions\TitanPulse\System\Services\Conditions\Operators; class NotEquals { public function passes(mixed $actual,mixed $expected): bool { return $actual != $expected; } }
