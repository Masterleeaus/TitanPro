<?php

namespace Modules\TitanNexus\Filament\Resources\LeadRecordResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Modules\TitanNexus\Filament\Resources\LeadRecordResource;

class ListLeadRecords extends ListRecords
{
    protected static string $resource = LeadRecordResource::class;


    /**
     * Safety shim: prevents legacy recursive discovery from registering this resource page as a resource.
     */
    public static function getPages(): array
    {
        return [];
    }
}
