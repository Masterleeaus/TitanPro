<?php

namespace Modules\ExampleModule\Filament\Pages;

use Filament\Pages\Page;

class DemoModuleControlPanel extends Page
{
    protected static ?string $navigationLabel = 'Demo Module';
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-cpu-chip';
    protected static ?string $slug = 'demo-module';
    protected string $view = 'example-module::filament.pages.demo-module-control-panel';
}
