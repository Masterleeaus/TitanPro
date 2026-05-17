<?php

namespace App\Providers\Filament\Concerns;

use Modules\TitanNexus\UI\Themes\MotionRuntimeTheme;

/**
 * Shared Filament plugin helpers for TitanPro panel providers.
 *
 * Provides two reusable methods:
 *  - `availablePlugins()` — safely instantiates optional plugins by class name,
 *    silently skipping any that are not installed.
 *  - `breezyPlugin()` — returns a configured Breezy BreezyCore instance with
 *    myProfile enabled, or an empty array when the package is absent.
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
     * Enables the my-profile page with user-menu registration.
     *
     * Breezy 2FA middleware is intentionally not enabled here because the
     * project uses Fortify's 2FA columns and older deployments may not have
     * Breezy's expected user contract methods available yet.
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
                ),
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
            fn (): \Illuminate\Contracts\View\View => view('filament.ui-inspector', [
                'motionRuntimeTheme' => MotionRuntimeTheme::make(),
            ]),
        ];
    }

    /**
     * Register the Titan OS shell render hook so the Business OS shell is
     * injected into every Filament panel. The shell view contains the app
     * launcher, assistant dock, workspace frame and generic UI renderer, but
     * does not expose unfinished module functionality.  This method returns
     * a render hook definition that can be spread into a panel via
     * ->renderHook(...$this->titanOsShellHooks()).
     *
     * @return array{0: string, 1: \Closure}
     */
    protected function titanOsShellHooks(): array
    {
        return [
            'panels::body.end',
            // When injecting the Business OS shell into panels, omit the workspace
            // frame to avoid interfering with the panel layout.  The layout
            // itself will handle asset loading.
            fn (): \Illuminate\Contracts\View\View => view('titan-os.shell', [
                // When injecting the shell into panels, omit the workspace frame
                // and enable asset loading so CSS/JS are available.
                'includeWorkspace' => false,
                'loadAssets' => true,
            ]),
        ];
    }
}
