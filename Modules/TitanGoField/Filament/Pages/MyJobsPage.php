<?php

namespace Modules\TitanGoField\Filament\Pages;

use Filament\Pages\Page;

class MyJobsPage extends Page
{
    protected static string|\\BackedEnum|null $navigationIcon = 'heroicon-o-briefcase';
    protected static ?string $navigationLabel = 'My Jobs';
    protected static string|\\UnitEnum|null $navigationGroup = 'Field';
    protected static ?int $navigationSort = 10;
    protected string $view = 'titangofield::pages.myjobspage';

    public function getTitle(): string
    {
        return 'My Jobs';
    }
}
