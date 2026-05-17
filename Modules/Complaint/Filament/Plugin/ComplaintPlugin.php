<?php

namespace Modules\Complaint\Filament\Plugin;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Modules\Complaint\Filament\Pages\EscalationPage;
use Modules\Complaint\Filament\Resources\ComplaintResource;
use Modules\Complaint\Filament\Widgets\SatisfactionWidget;

class ComplaintPlugin implements Plugin
{
    public function getId(): string
    {
        return 'complaint';
    }

    public function register(Panel $panel): void
    {
        $panel
            ->resources([
                ComplaintResource::class,
            ])
            ->pages([
                EscalationPage::class,
            ])
            ->widgets([
                SatisfactionWidget::class,
            ]);
    }

    public function boot(Panel $panel): void
    {
    }

    public static function make(): static
    {
        return app(static::class);
    }
}
