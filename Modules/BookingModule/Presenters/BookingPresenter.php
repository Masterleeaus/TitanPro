<?php

namespace Modules\BookingModule\Presenters;

class BookingPresenter
{
    public function statusLabel(?string $status): string
    {
        return ucwords(str_replace(['_', '-'], ' ', (string) $status ?: 'unknown'));
    }

    public function money($amount): string
    {
        return number_format((float) $amount, 2);
    }
}
