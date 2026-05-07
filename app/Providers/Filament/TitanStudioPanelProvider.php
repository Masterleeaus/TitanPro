<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Contracts\Plugin;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

/**
 * TitanStudio — Workflow builder, automation, and CMS studio panel.
 */
class TitanStudioPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('titanstudio')
            ->path('titanstudio')
            ->brandName('TitanStudio')
            ->colors([
                'primary' => Color::Pink,
            ])
            ->plugins($this->availablePlugins([
                'Relaticle\\Flowforge\\FlowforgePlugin',
            ]))
            ->resources([
                \App\Filament\Resources\MessageTemplateResource::class,
                \App\Filament\Resources\CmsPageResource::class,
                \App\Filament\Resources\JobTypeChecklistItemResource::class,
                \App\Filament\Resources\JobChecklistItemResource::class,
            ])
            ->discoverResources(in: app_path('Filament/TitanStudio/Resources'), for: 'App\\Filament\\TitanStudio\\Resources')
            ->discoverPages(in: app_path('Filament/TitanStudio/Pages'), for: 'App\\Filament\\TitanStudio\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/TitanStudio/Widgets'), for: 'App\\Filament\\TitanStudio\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }

    /**
     * @param  array<int, class-string>  $pluginClasses
     * @return array<int, Plugin>
     */
    private function availablePlugins(array $pluginClasses): array
    {
        $plugins = [];

        foreach ($pluginClasses as $pluginClass) {
            if (! class_exists($pluginClass)
                || ! is_subclass_of($pluginClass, Plugin::class)
                || ! method_exists($pluginClass, 'make')) {
                continue;
            }

            $plugins[] = $pluginClass::make();
        }

        return $plugins;
    }
}
