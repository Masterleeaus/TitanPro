<?php

declare(strict_types=1);

namespace Modules\Dispatch\Filament\Pages;

use Filament\Pages\Page;
use Modules\Dispatch\Models\AssignShift;
use Modules\Dispatch\Models\Shift;
use Modules\Dispatch\Models\DispatchAppointment;
use Modules\Dispatch\Models\DispatchWorkOrder;

class DispatchPanelPage extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-map';
    protected static string|\UnitEnum|null $navigationGroup = 'Dispatch';
    protected static ?string $navigationLabel = 'Dispatch Panel';
    protected static ?int $navigationSort = 10;

    public function getView(): string
    {
        return 'dispatch::filament.pages.dispatch-panel';
    }

    public function getTitle(): string
    {
        return 'Dispatch Panel';
    }

    public function getViewData(): array
    {
        $appointments = DispatchAppointment::query()
            ->with(['workOrder', 'technician', 'shiftAssignment.shift'])
            ->latest('starts_at')
            ->limit(50)
            ->get();

        return [
            'openWorkOrders' => DispatchWorkOrder::query()
                ->with(['technician', 'primaryShiftAssignment.shift'])
                ->whereNotIn('status', ['completed', 'done', 'cancelled', 'canceled'])
                ->latest('scheduled_for')
                ->limit(50)
                ->get(),
            'appointments' => $appointments,
            'shiftTemplates' => Shift::query()->where('publish', 1)->orderBy('name')->get(),
            'unlinkedShiftAssignments' => AssignShift::query()
                ->with(['shift', 'employee'])
                ->whereNull('work_order_id')
                ->latest()
                ->limit(25)
                ->get(),
        ];
    }
}
