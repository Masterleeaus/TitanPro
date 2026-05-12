<?php

namespace Modules\TitanProAdmin\Filament\Pages;

use Filament\Pages\Page;

class AuditLogPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel = 'Audit Log';
    protected static ?string $navigationGroup = 'System';
    protected static ?int $navigationSort = 30;
    protected static string $view = 'titanproadmin::pages.audit-log';

    public function getTitle(): string
    {
        return 'Audit Log';
    }
}
