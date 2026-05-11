## Issue Summary
TitanNexus needed to be fully installed as a dedicated Filament panel at `/titannexus` with correct role access, canonical legacy aliases, initial navigation resources for growth workflows, and test coverage proving owner access for vertical packs and training content.

## Root Cause
The repository had partial TitanNexus wiring (panel provider and provider registration), but it lacked the `/titan-nexus` alias, lacked initial TitanNexus resource/page surfaces, did not explicitly enforce owner/admin-only panel access in `canAccessPanel()`, and did not include targeted tests for TitanNexus vertical/training access.

## Changes Made
- `app/Models/User.php`
  - Added panel-specific role gate for `titannexus` so only `owner` and `admin` can access it.
- `app/Providers/Filament/TitanNexusPanelProvider.php`
  - Updated panel purpose docblock to match required use cases.
  - Registered initial TitanNexus pages in panel navigation: Verticals, Lead Pipeline, Training Content, Marketing Campaigns.
- `app/Filament/TitanNexus/Pages/Verticals.php`
  - Added TitanNexus Verticals page class and navigation metadata.
- `app/Filament/TitanNexus/Pages/LeadPipeline.php`
  - Added TitanNexus Lead Pipeline page class and navigation metadata.
- `app/Filament/TitanNexus/Pages/TrainingContent.php`
  - Added TitanNexus Training Content page class and navigation metadata.
- `app/Filament/TitanNexus/Pages/MarketingCampaigns.php`
  - Added TitanNexus Marketing Campaigns page class and navigation metadata.
- `resources/views/filament/titan-nexus/pages/verticals.blade.php`
  - Added initial panel view for vertical pack management.
- `resources/views/filament/titan-nexus/pages/lead-pipeline.blade.php`
  - Added initial panel view for lead pipeline monitoring.
- `resources/views/filament/titan-nexus/pages/training-content.blade.php`
  - Added initial panel view for training modules/content access.
- `resources/views/filament/titan-nexus/pages/marketing-campaigns.blade.php`
  - Added initial panel view for marketing automation campaign management.
- `routes/web.php`
  - Kept canonical `/titan-grow` permanent redirect to `/titannexus` in the legacy alias block.
  - Added `/titan-nexus` permanent redirect alias to `/titannexus`.
- `resources/js/components/AppSidebar.vue`
  - Added TitanNexus product switcher navigation link (owner/admin visibility).
- `config/titan_panels.php`
  - Expanded TitanNexus panel description to reflect the required target use cases.
- `tests/Feature/PanelRoutingTest.php`
  - Added `/titan-nexus` alias assertion.
  - Added test that `super_admin` is denied on `/titannexus`.
  - Added test proving owner can access `/titannexus/verticals` and `/titannexus/training-content`.
- `tests/Feature/WelcomeTest.php`
  - Added explicit legacy alias redirect test for `/titan-nexus`.

## Tests Added or Updated
- Updated `tests/Feature/PanelRoutingTest.php`:
  - `legacy_panel_aliases` dataset now includes `/titan-nexus` -> `/titannexus`
  - Added `titannexus panel is restricted from super admin role`
  - Added `owner can access TitanNexus verticals and training content pages`
- Updated `tests/Feature/WelcomeTest.php`:
  - Added `titan nexus alias permanently redirects to titannexus panel path`

## Next Steps
- Install PHP dependencies in a PHP `^8.4` environment and run focused Pest tests (`PanelRoutingTest`, `WelcomeTest`) plus full suite validation.
- Optionally replace placeholder TitanNexus pages with full Filament Resources backed by dedicated domain models as those models are finalized.
