<?php

namespace Modules\Complaint\Filament\Infolists;

use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;

class ComplaintInfolist
{
    public static function make(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            TextEntry::make('subject'),
            TextEntry::make('status')->badge(),
            TextEntry::make('priority')->badge(),
            TextEntry::make('resolution_outcome')->placeholder('Pending resolution'),
            TextEntry::make('created_at')->dateTime(),
        ]);
    }
}
