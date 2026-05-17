<?php

declare(strict_types=1);

namespace Modules\Payroll\Filament\Plugin;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Pages\Page;
use Filament\Resources\Resource;
use Filament\Widgets\Widget;

class PayrollPlugin implements Plugin
{
    public function getId(): string
    {
        return 'payroll';
    }

    public function register(Panel $panel): void
    {
        $resources = $this->existing(Resource::class, [
            \Modules\Payroll\Filament\Resources\CleanerPayrollRunResource::class,
        ]);

        $pages = $this->existing(Page::class, [
            \Modules\Payroll\Filament\Pages\CleaningPayrollSettingsPage::class,
        ]);

        $widgets = $this->existing(Widget::class, [
            \Modules\Payroll\Filament\Widgets\CleanerPayrollKpiWidget::class,
            \Modules\Payroll\Filament\Widgets\PayrollRunOverviewWidget::class,
        ]);

        if ($resources !== []) {
            $panel->resources($resources);
        }

        if ($pages !== []) {
            $panel->pages($pages);
        }

        if ($widgets !== []) {
            $panel->widgets($widgets);
        }
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public static function make(): static
    {
        return app(static::class);
    }

    /**
     * @param  class-string  $requiredParent
     * @param  array<int, class-string>  $classes
     * @return array<int, class-string>
     */
    private function existing(string $requiredParent, array $classes): array
    {
        return array_values(array_filter(
            $classes,
            static fn (string $class): bool => class_exists($class) && is_subclass_of($class, $requiredParent)
        ));
    }
}
