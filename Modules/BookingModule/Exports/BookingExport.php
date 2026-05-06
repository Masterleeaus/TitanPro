<?php

namespace Modules\BookingModule\Exports;

class BookingExport
{
    public function headings(): array
    {
        return ['ID', 'Company', 'Status', 'Customer', 'Scheduled For'];
    }
}
