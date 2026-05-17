<?php

namespace Modules\ZeroPayModule\Presenters;

class ZeroPaySessionPresenter
{
    public function statusLabel($record): string
    {
        return ucfirst(str_replace('_', ' ', $record->status));
    }
}
