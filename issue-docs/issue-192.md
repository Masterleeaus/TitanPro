# Issue 192 — [PANEL] Install TitanQuotes Filament panel at /titanquotes

## Summary of Fixes Applied
- Completed TitanQuotes panel wiring updates for required branding and access rules.
- Added TitanQuotes-specific Filament resources for quote operations (quotes, cleaning packages, customers read-only, and add-ons).
- Added a quote pipeline dashboard page with draft/sent/accepted/converted counts.
- Added/updated tests for TitanQuotes access rules and panel quote workflows.

## Files Changed
- `app/Providers/Filament/TitanQuotesPanelProvider.php`
  - Set brand name to `TitanQuotes`.
  - Registered `QuotePipelineDashboard` page.
- `config/titan_panels.php`
  - Updated TitanQuotes allowed roles to `owner`, `admin`, `bookkeeper`.
- `app/Models/User.php`
  - Added TitanQuotes-specific panel access gate for `owner`, `admin`, `bookkeeper`.
- `app/Filament/TitanQuotes/Pages/QuotePipelineDashboard.php`
  - Added page logic for quote status/converted counts.
- `resources/views/filament/titanquotes/pages/quote-pipeline-dashboard.blade.php`
  - Added dashboard UI for pipeline metrics.
- `app/Filament/TitanQuotes/Resources/EstimateResource.php`
- `app/Filament/TitanQuotes/Resources/EstimateResource/Pages/ListEstimates.php`
- `app/Filament/TitanQuotes/Resources/EstimateResource/Pages/CreateEstimate.php`
- `app/Filament/TitanQuotes/Resources/EstimateResource/Pages/EditEstimate.php`
  - Added TitanQuotes estimate resource/pages and `Send Quote` action.
- `app/Filament/TitanQuotes/Resources/EstimatePackageResource.php`
- `app/Filament/TitanQuotes/Resources/EstimatePackageResource/Pages/ListEstimatePackages.php`
- `app/Filament/TitanQuotes/Resources/EstimatePackageResource/Pages/CreateEstimatePackage.php`
- `app/Filament/TitanQuotes/Resources/EstimatePackageResource/Pages/EditEstimatePackage.php`
  - Added TitanQuotes cleaning packages management resource/pages.
- `app/Filament/TitanQuotes/Resources/CustomerResource.php`
- `app/Filament/TitanQuotes/Resources/CustomerResource/Pages/ListCustomers.php`
  - Added TitanQuotes customers resource as read-only list.
- `app/Filament/TitanQuotes/Resources/ItemResource.php`
- `app/Filament/TitanQuotes/Resources/ItemResource/Pages/ListItems.php`
- `app/Filament/TitanQuotes/Resources/ItemResource/Pages/CreateItem.php`
- `app/Filament/TitanQuotes/Resources/ItemResource/Pages/EditItem.php`
  - Added TitanQuotes add-ons resource/pages.
- `tests/Feature/PanelRoutingTest.php`
  - Added TitanQuotes role access tests (bookkeeper allowed, dispatcher forbidden).
- `tests/Feature/TitanQuotesPanelTest.php`
  - Added TitanQuotes panel workflow tests (estimate list/create/send surfaces, pipeline page, read-only customers).

## Validation Notes
- `php -l` passed for all changed/new PHP files.
- Full/targeted Pest tests could not run in this environment because `vendor/` dependencies are not installed (`./vendor/bin/pest` missing).
- Frontend/backend baseline tooling was unavailable in this environment (`eslint` not installed; artisan/composer test command blocked by missing `vendor/autoload.php`).

## Next Steps
1. Install project dependencies (`composer install`, `npm install`) in a PHP-compatible environment.
2. Run targeted tests:
   - `./vendor/bin/pest tests/Feature/PanelRoutingTest.php tests/Feature/TitanQuotesPanelTest.php`
3. Run full suite and linters before merge.
