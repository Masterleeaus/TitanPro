<?php

namespace Modules\BookingModule\Filament;

use Filament\Contracts\Plugin;
use Filament\Panel;

class BookingModulePlugin implements Plugin
{
    public static function make(): static
    {
        return new static();
    }

    public function getId(): string
    {
        return 'bookingmodule';
    }

    public function register(Panel $panel): void
    {
        $panel->resources($this->resources());
        $panel->pages($this->pages());
        $panel->widgets($this->widgets());
    }

    public function boot(Panel $panel): void {}

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
        $pages = [
            \Modules\BookingModule\Filament\Pages\BookingModulePage::class,
        ];

        return array_values(array_filter(
            $pages,
            static fn (string $page): bool => class_exists($page)
                && is_subclass_of($page, \Filament\Pages\Page::class),
        ));
    }

    public function widgets(): array
    {
        $widgets = [
            \Modules\BookingModule\Filament\Widgets\BookingStatsWidget::class,
        ];

        return array_values(array_filter(
            $widgets,
            static fn (string $widget): bool => class_exists($widget)
                && is_subclass_of($widget, \Filament\Widgets\Widget::class),
        ));
    }
}
