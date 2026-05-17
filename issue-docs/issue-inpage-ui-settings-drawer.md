# Issue In-Page UI Settings Drawer

## Files changed
- public/titan-ui-settings/titan-ui-settings.css
- public/titan-ui-settings/titan-ui-settings.js
- app/Providers/TitanInPageUiSettingsServiceProvider.php
- app/Http/Middleware/InjectTitanInPageUiSettings.php
- scripts/install-inpage-ui-settings-drawer.sh

## Fix applied
- Adds a floating gear drawer on real dashboards instead of using a cramped iframe preview.
- Provides live controls for primary color, radius, density, font scale, card padding, sidebar width, collapsed sidebar preview, and mobile compact tables.
- Changes apply instantly on the page being edited.
- Settings are stored client-side in localStorage for immediate preview.
- Middleware injects assets into HTML responses so it works across Filament panels without editing each panel layout.

## Next steps
- Persist settings to `titan_theme_tokens` through a Livewire/API save endpoint.
- Scope settings per panel/user/role.
- Add table-specific column visibility controls backed by server config.
