# PASS53 UI Inspector In-Page Settings

## Deep scan finding
The visible bottom-right settings sidebar is not controlled by the UI Studio iframe view. It is injected by `App\Providers\Filament\Concerns\RegistersFilamentPlugins::uiInspectorHook()` using `resources/views/filament/ui-inspector.blade.php` and `public/js/titan/ui-inspector.js`.

The TitanPro panel also registers the optional package `Andreia\FilamentUiSwitcher\FilamentUiSwitcherPlugin`, but the repo ZIP does not include vendor sources, so the patch targets the app-owned inspector layer that is actually present in the repo.

## Files changed
- resources/views/filament/ui-inspector.blade.php
- public/js/titan/ui-inspector.js
- scripts/install-ui-inspector-inpage-settings.sh

## Fix applied
- Gear now opens the settings drawer directly instead of requiring component-pick mode.
- Added page-level UI controls into the existing sidebar: layout, primary color, font, sidebar width, compact density, icon sidebar.
- Applies changes live to the current dashboard page using CSS variables/body classes.
- Stores live settings in localStorage for immediate reuse.

## Next steps
- Persist these settings server-side into `titan_theme_tokens`.
- Scope by panel, user, role, or organization.
