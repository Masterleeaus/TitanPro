<?php

namespace Modules\ExampleModule\Filament\Pages;

use Filament\Pages\Page;

class DemoModuleControlPanel extends Page
{
    protected static ?string $navigationLabel = 'Demo Module';
    protected static ?string $navigationIcon = 'heroicon-o-cpu-chip';
    protected static ?string $slug = 'demo-module';
    protected static string $view = 'example-module::filament.pages.demo-module-control-panel';
}
