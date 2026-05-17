<?php

declare(strict_types=1);

namespace Modules\Dispatch\Filament\Pages;

use Filament\Pages\Page;
use Modules\Dispatch\Models\DispatchRoute;

class RouteMapPage extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-map';
    protected static string|\UnitEnum|null $navigationGroup = 'Dispatch';
    protected static ?string $navigationLabel = 'Route Map';
    protected static ?int $navigationSort = 12;
    protected static string $view = 'dispatch::filament.pages.route-map';

    public function getViewData(): array
    {
        return [
            'routes' => DispatchRoute::query()->with(['technician', 'stops.customerLocation', 'stops.workOrder'])->latest('route_date')->limit(20)->get(),
        ];
    }
}
