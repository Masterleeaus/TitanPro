<?php

namespace Modules\TitanNexus\Filament\Resources\LeadRecordResource\Pages;

class ListLeadRecords
{
    protected static string $resource = \Modules\TitanNexus\Filament\Resources\LeadRecordResource::class;


    /**
     * Safety shim: prevents legacy recursive discovery from registering this resource page as a resource.
     */
    public static function getPages(): array
    {
        return [];
    }
}
