<?php

namespace Modules\Complaint\Filament\Resources\ComplaintResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Modules\Complaint\Filament\Resources\ComplaintResource;

class ListComplaints extends ListRecords
{
    protected static string $resource = ComplaintResource::class;
}
