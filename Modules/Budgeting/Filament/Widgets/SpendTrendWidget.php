<?php

declare(strict_types=1);

namespace Modules\Budgeting\Filament\Widgets;

use Filament\Widgets\Widget;

class SpendTrendWidget extends Widget
{
    protected static string $view = 'budgeting::widgets.spend-trend';

    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';
}
