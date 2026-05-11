<?php
namespace App\Extensions\TitanPulse\System\Services\Conditions;
use App\Extensions\TitanPulse\System\Services\Conditions\Operators\Contains;
use App\Extensions\TitanPulse\System\Services\Conditions\Operators\ContainsAny;
use App\Extensions\TitanPulse\System\Services\Conditions\Operators\DateWithin;
use App\Extensions\TitanPulse\System\Services\Conditions\Operators\Equals;
use App\Extensions\TitanPulse\System\Services\Conditions\Operators\GreaterThan;
use App\Extensions\TitanPulse\System\Services\Conditions\Operators\IsEmpty;
use App\Extensions\TitanPulse\System\Services\Conditions\Operators\NotEquals;
class ConditionRegistry {
    public function all(): array { return ['eq'=>new Equals(),'neq'=>new NotEquals(),'contains'=>new Contains(),'contains_any'=>new ContainsAny(),'gt'=>new GreaterThan(),'empty'=>new IsEmpty(),'date_within'=>new DateWithin()]; }
    public function get(string $operator): object { return $this->all()[$operator] ?? new Equals(); }
}
