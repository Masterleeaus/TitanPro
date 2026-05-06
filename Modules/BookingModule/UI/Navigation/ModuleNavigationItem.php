<?php

namespace Modules\BookingModule\UI\Navigation;

class ModuleNavigationItem
{
    public function __construct(public string $label, public ?string $route = null, public ?string $permission = null) {}

    public function toArray(): array { return get_object_vars($this); }
}
