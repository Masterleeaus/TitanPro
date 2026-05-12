<?php

namespace Modules\CleaningJobs\Filament\Pages;

use Filament\Pages\Page;

class JobSchedulePage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $navigationLabel = 'Schedule';
    protected static ?string $navigationGroup = 'Cleaning Jobs';
    protected static ?int $navigationSort = 2;

    public function getView(): string
    {
        return 'cleaningjobs::filament.pages.job-schedule';
    }
}
