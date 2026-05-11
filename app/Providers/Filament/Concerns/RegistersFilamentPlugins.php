<?php

namespace App\Providers\Filament\Concerns;

/**
 * Shared Filament plugin helpers for TitanPro panel providers.
 *
 * Provides two reusable methods:
 *  - `availablePlugins()` — safely instantiates optional plugins by class name,
 *    silently skipping any that are not installed.
 *  - `breezyPlugin()` — returns a configured Breezy BreezyCore instance with
 *    myProfile and 2FA enabled, or an empty array when the package is absent.
 */
trait RegistersFilamentPlugins
{
    /**
     * Instantiate optional Filament plugins without breaking the panel when a
     * package has not been installed.
     *
     * @param  array<int, class-string>  $pluginClasses
     * @return array<int, object>
     */
    private function availablePlugins(array $pluginClasses): array
    {
        $plugins = [];

        foreach ($pluginClasses as $pluginClass) {
            if (! class_exists($pluginClass) || ! method_exists($pluginClass, 'make')) {
                continue;
            }

            $plugins[] = $pluginClass::make();
        }

        return $plugins;
    }

    /**
     * Return a configured BreezyCore plugin array (empty array when package is absent).
     *
     * Enables the my-profile page with user-menu registration and integrates
     * Breezy's two-factor authentication UI, complementing the existing Fortify
     * 2FA backend.
     *
     * @return array<int, object>
     */
    private function breezyPlugin(): array
    {
        if (! class_exists(\Jeffgreco13\FilamentBreezy\BreezyCore::class)) {
            return [];
        }

        return [
            \Jeffgreco13\FilamentBreezy\BreezyCore::make()
                ->myProfile(
                    shouldRegisterUserMenu: true,
                    shouldRegisterNavigation: false,
                    hasAvatars: false,
                    slug: 'my-profile',
                )
                ->enableTwoFactorAuthentication(),
        ];
    }

    /**
     * Register the Visual UI Inspector render hook so the floating inspector
     * button and property sidebar are available on every page in this panel.
     *
     * Inject into a panel via:
     *   ->renderHook(...$this->uiInspectorHook())
     *
     * @return array{0: string, 1: \Closure}
     */
    private function uiInspectorHook(): array
    {
        return [
            'panels::body.end',
            fn (): \Illuminate\Contracts\View\View => view('filament.ui-inspector'),
        ];
    }
}
