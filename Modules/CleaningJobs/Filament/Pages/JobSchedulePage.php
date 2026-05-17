<?php
declare(strict_types=1);
namespace Modules\CleaningJobs\Filament\Pages;

use Filament\Pages\Page;

class JobSchedulePage extends Page
{
    protected static string $view = 'cleaningjobs::filament.pages.job-schedule';
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-calendar-days';
    protected static string|\UnitEnum|null $navigationGroup = 'Cleaning Jobs';
    protected static ?string $navigationLabel = 'Job Schedule';
    protected static bool $shouldRegisterNavigation = false;
    protected static ?int $navigationSort = 40;

    public function getTitle(): string
    {
        return 'Job Schedule';
    }
}
