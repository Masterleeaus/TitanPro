<?php

namespace Modules\CRMCore\Filament\Pages;

use Filament\Pages\Page;

class CRMCoreOverview extends Page
{
    protected string $view = 'crmcore::filament.pages.crmcore-overview';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-squares-2x2';
    protected static string|\UnitEnum|null $navigationGroup = 'CRM Core';
    protected static ?string $navigationLabel = 'Customer Panel';
    protected static ?string $title = 'Customer Panel';
    protected static ?int $navigationSort = 5;
}
