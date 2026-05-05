<?php

namespace Modules\BookingModule\UI\Cards;

class StatusCard
{
    public function __construct(public string $label, public mixed $value, public ?string $tone = null) {}

    public function toArray(): array { return get_object_vars($this); }
}
