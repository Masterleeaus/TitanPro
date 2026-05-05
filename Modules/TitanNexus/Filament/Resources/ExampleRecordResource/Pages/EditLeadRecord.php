<?php

namespace Modules\TitanNexus\Filament\Resources\LeadRecordResource\Pages;

class EditLeadRecord
{
    // Use UpdateLeadRecordAction in handleRecordUpdate().


    /**
     * Safety shim: prevents legacy recursive discovery from registering this resource page as a resource.
     */
    public static function getPages(): array
    {
        return [];
    }
}
