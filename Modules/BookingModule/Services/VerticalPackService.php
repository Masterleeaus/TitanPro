<?php

namespace Modules\BookingModule\Services;

class VerticalPackService
{
    public function supported(): array
    {
        return array_keys((array) config('bookingmodule.verticals.supported', []));
    }

    public function resolve(?string $vertical = null): array
    {
        $vertical = $vertical ?: (string) config('bookingmodule.verticals.default', 'services');
        return (array) config("bookingmodule.verticals.supported.$vertical", []);
    }
}
