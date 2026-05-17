<?php

declare(strict_types=1);

namespace Modules\Dispatch\Filament\Pages;

use Filament\Pages\Page;
use Modules\Dispatch\Models\DispatchWorkOrder;
use Modules\Dispatch\Models\AssignShift;
use Modules\Dispatch\Models\TechnicianProfile;

class DragDropSchedulerPage extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-calendar-days';
    protected static string|\UnitEnum|null $navigationGroup = 'Dispatch';
    protected static ?string $navigationLabel = 'Drag Scheduler';
    protected static ?int $navigationSort = 11;
    protected static string $view = 'dispatch::filament.pages.drag-drop-scheduler';

    public function getViewData(): array
    {
        return [
            'technicians' => TechnicianProfile::query()->with(['user', 'defaultZone'])->where('active', true)->orderBy('display_name')->limit(25)->get(),
            'unscheduledWorkOrders' => DispatchWorkOrder::query()->whereNull('scheduled_for')->whereNotIn('status', ['completed', 'done', 'cancelled', 'canceled'])->latest()->limit(25)->get(),
            'scheduledAssignments' => AssignShift::query()->with(['shift', 'employee', 'workOrder'])->whereNotNull('work_order_id')->latest('date_added')->limit(50)->get(),
        ];
    }
}
