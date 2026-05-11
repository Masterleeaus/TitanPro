<?php namespace App\Extensions\TitanPulse\System\Services\Conditions\Operators; class IsEmpty { public function passes(mixed $actual,mixed $expected=null): bool { return blank($actual); } }
