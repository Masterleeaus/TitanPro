<?php

namespace Modules\BookingModule\Filament;

class BookingModulePlugin
{
    public function getId(): string
    {
        return 'bookingmodule';
    }

    public function resources(): array
    {
        return array_values(array_filter([
            class_exists(\Modules\BookingModule\Filament\Resources\BookingResource::class) ? \Modules\BookingModule\Filament\Resources\BookingResource::class : null,
            class_exists(\Modules\BookingModule\Filament\Resources\AppointmentResource::class) ? \Modules\BookingModule\Filament\Resources\AppointmentResource::class : null,
            class_exists(\Modules\BookingModule\Filament\Resources\ScheduleResource::class) ? \Modules\BookingModule\Filament\Resources\ScheduleResource::class : null,
        ]));
    }

    public function pages(): array
    {
        return array_values(array_filter([
            class_exists(\Modules\BookingModule\Filament\Pages\BookingModulePage::class) ? \Modules\BookingModule\Filament\Pages\BookingModulePage::class : null,
        ]));
    }

    public function widgets(): array
    {
        return array_values(array_filter([
            class_exists(\Modules\BookingModule\Filament\Widgets\BookingStatsWidget::class) ? \Modules\BookingModule\Filament\Widgets\BookingStatsWidget::class : null,
        ]));
    }
}
