# ExampleModule UI Kit

Reusable UI building blocks for the module one-page control panel.

This folder is additive only. It does not replace Filament resources, domain Actions, Services, Policies, or manifests.

## Layers

- `ControlPanel/` page composition and section registry
- `Widgets/` reusable dashboard widgets
- `Cards/` KPI, status, media, and action cards
- `Tables/` table presets and column helpers
- `Forms/` field groups and module settings sections
- `Infolists/` record summary layouts
- `Actions/` UI action definitions that call module Actions/Services
- `Navigation/` menu items, shortcuts, breadcrumbs
- `Timeline/` activity and audit UI adapters
- `Media/` Curator/Uppy integration adapters
- `Charts/` Apex chart configuration adapters
- `Progress/` progress bars, stepper models, SLA display
- `Badges/` status and risk badges
- `Tabs/` page tabs and bottom table panes
- `EmptyStates/` operator-friendly placeholders
- `Themes/` CSS tokens and layout classes
- `Adapters/` wrappers around optional third-party plugins

All plugin-specific code should stay behind adapters so modules remain portable.
