# Issue 321 — Replace TitanNexus placeholder pages with org-scoped Filament Resources

## Files Changed

- `app/Providers/Filament/TitanNexusPanelProvider.php`
  - Removed placeholder page registrations/imports so TitanNexus now relies on discovered resources.
- `app/Filament/TitanNexus/Pages/Verticals.php` (removed)
- `app/Filament/TitanNexus/Pages/LeadPipeline.php` (removed)
- `app/Filament/TitanNexus/Pages/TrainingContent.php` (removed)
- `app/Filament/TitanNexus/Pages/MarketingCampaigns.php` (removed)
- `resources/views/filament/titan-nexus/pages/verticals.blade.php` (removed)
- `resources/views/filament/titan-nexus/pages/lead-pipeline.blade.php` (removed)
- `resources/views/filament/titan-nexus/pages/training-content.blade.php` (removed)
- `resources/views/filament/titan-nexus/pages/marketing-campaigns.blade.php` (removed)

### New domain models
- `app/Models/VerticalPack.php`
- `app/Models/LeadPipelineEntry.php`
- `app/Models/TrainingContentModule.php`
- `app/Models/MarketingCampaign.php`

### New migrations
- `database/migrations/2026_05_11_180300_create_vertical_packs_table.php`
- `database/migrations/2026_05_11_180301_create_lead_pipeline_entries_table.php`
- `database/migrations/2026_05_11_180302_create_training_content_modules_table.php`
- `database/migrations/2026_05_11_180303_create_marketing_campaigns_table.php`

### New factories
- `database/factories/VerticalPackFactory.php`
- `database/factories/LeadPipelineEntryFactory.php`
- `database/factories/TrainingContentModuleFactory.php`
- `database/factories/MarketingCampaignFactory.php`

### New TitanNexus Filament resources
- `app/Filament/TitanNexus/Resources/VerticalPackResource.php`
- `app/Filament/TitanNexus/Resources/LeadPipelineEntryResource.php`
- `app/Filament/TitanNexus/Resources/TrainingContentModuleResource.php`
- `app/Filament/TitanNexus/Resources/MarketingCampaignResource.php`
- `app/Filament/TitanNexus/Resources/**/Pages/*.php` (list/create/view/edit pages for each resource)

### New tests
- `tests/Feature/TitanNexus/TitanNexusResourcesTest.php`

## Fixes Applied

- Replaced static TitanNexus placeholders with four full Filament resources backed by dedicated domain models.
- Added CRUD pages (list/create/view/edit) for vertical packs, lead pipeline entries, training modules, and marketing campaigns.
- Added explicit org scoping in each resource via `getEloquentQuery()` with null-safe `whereRaw('1 = 0')` fallback.
- Added role checks in each resource so only owner/admin can use resource actions (super-admin denied).
- Preserved existing TitanNexus routes by setting slugs to:
  - `verticals`
  - `lead-pipeline`
  - `training-content`
  - `marketing-campaigns`
- Added Pest feature tests for owner/admin CRUD route access, super-admin denial, and cross-org 404 isolation per resource.

## Validation

- `php -l` run across all changed PHP files: **pass**.
- Could not run Pest in this environment because `vendor/` is unavailable and Composer install is blocked on PHP 8.3.6 while project dependencies require PHP 8.4+.

## Next Steps

- In a PHP 8.4+ environment: run migrations and execute `./vendor/bin/pest tests/Feature/TitanNexus/TitanNexusResourcesTest.php`.
- Run full project test suite once dependencies are installed.
- If desired, add resource-specific policy classes for finer-grained action control beyond panel role gating.
