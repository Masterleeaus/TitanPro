## Issue Summary
Implemented a multi-tenant white-label branding engine for organization-owned panels with per-org branding persistence, UI Studio branding controls, and runtime panel branding fallback behavior.

## Files Changed
- `app/Models/OrganizationBranding.php`
- `database/migrations/2026_05_07_175500_create_organization_brandings_table.php`
- `app/Support/OrganizationBrandingResolver.php`
- `app/Models/Organization.php`
- `app/Filament/Pages/UiStudio.php`
- `resources/views/filament/pages/ui-studio.blade.php`
- `app/Filament/TitanStudio/Pages/UiStudio.php`
- `app/Providers/Filament/GroundZeroPanelProvider.php`
- `app/Providers/Filament/TitanStudioPanelProvider.php`
- `app/Providers/Filament/TitanQuotesPanelProvider.php`
- `app/Providers/Filament/TitanNexusPanelProvider.php`
- `app/Providers/Filament/TitanGoPanelProvider.php`
- `app/Providers/Filament/TitanSoloPanelProvider.php`
- `app/Providers/Filament/ZeroPayPanelProvider.php`
- `app/Providers/Filament/ZeroFussPanelProvider.php`
- `tests/Feature/OrganizationBrandingTest.php`

## Fixes Applied
- Added `organization_brandings` storage with required white-label fields and org-unique tenancy binding.
- Added `OrganizationBranding` model and `Organization::branding()` relation.
- Added `OrganizationBrandingResolver` with per-org override + platform fallback logic.
- Extended UI Studio with a dedicated **Branding** tab for org owners/admins:
  - panel name
  - logo upload
  - favicon upload
  - primary/secondary colors
  - font family
  - background type/value
  - live preview before publish
- Added image validation on logo/favicon uploads during publish.
- Saved org-specific menu and dashboard layout payloads with branding publish.
- Injected runtime panel branding (brand name, logo, favicon, primary color) in tenant panel providers.
- Added focused feature tests for branding persistence and resolver fallback/override behavior.

## Next Steps
- Wire `menu_items` into actual Filament navigation builders for full runtime menu override.
- Apply `font_family` and background settings as panel runtime CSS variables in Filament shell for complete visual parity.
