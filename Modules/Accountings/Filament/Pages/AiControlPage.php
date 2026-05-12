<?php

namespace Modules\Accountings\Filament\Pages;

use Filament\Pages\Page;
use Modules\Accountings\Agents\MoneyAgent;

class AiControlPage extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-sparkles';
    protected static string|\UnitEnum|null $navigationGroup = 'Finance';
    protected static ?string $navigationLabel = 'AI Control';
    protected static ?string $title = 'AI Control';
    protected static ?int $navigationSort = 223;
    protected string $view = 'accountings::filament.pages.workspace-placeholder';

    public function toolLog(): array
    {
        return array_map(static fn (string $toolClass): array => [
            'tool' => $toolClass,
            'enabled' => class_exists($toolClass),
        ], (new MoneyAgent)->tools());
    }
}
