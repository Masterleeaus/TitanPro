<?php

namespace Modules\TitanGoField\Filament\Pages;

use Filament\Pages\Page;

class MyJobsPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-briefcase';
    protected static ?string $navigationLabel = 'My Jobs';
    protected static ?string $navigationGroup = 'Field';
    protected static ?int $navigationSort = 10;
    protected static string $view = 'titangofield::pages.myjobspage';

    public function getTitle(): string
    {
        return 'My Jobs';
    }
}
