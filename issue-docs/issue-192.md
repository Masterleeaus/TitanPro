# Issue 192 — Panel updates (TitanQuotes + TitanSolo)

## TitanQuotes Summary
- Completed TitanQuotes panel wiring updates for required branding and access rules.
- Added TitanQuotes-specific Filament resources for quote operations (quotes, cleaning packages, customers read-only, and add-ons).
- Added a quote pipeline dashboard page with draft/sent/accepted/converted counts.
- Added/updated tests for TitanQuotes access rules and panel quote workflows.

## TitanQuotes Files Changed
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

## TitanSolo Summary
- Installed and completed the TitanSolo Filament panel at `/titansolo` for single-operator cleaning businesses.
- Added a streamlined solo dashboard, solo-scoped resources, role/plan access enforcement, and product switcher link support.

## TitanSolo Root Cause
- TitanSolo panel plumbing was only partially present: provider/config registration existed, but there were no TitanSolo resources/pages/widgets to deliver the required workflow.
- Panel access rules did not distinguish TitanSolo from broader owner/admin panels, so single-operator gating was missing.

## TitanSolo Changes Made
- Updated `app/Providers/Filament/TitanSoloPanelProvider.php`:
  - brand name set to `TitanSolo`
  - uses dedicated TitanSolo dashboard page
  - registers solo overview widget
- Added TitanSolo dashboard + widget implementation:
  - `app/Filament/TitanSolo/Pages/Dashboard.php`
  - `app/Filament/TitanSolo/Widgets/SoloOverviewWidget.php`
  - `resources/views/filament/titansolo/widgets/solo-overview-widget.blade.php`
- Added simplified TitanSolo resource set (no team/dispatch resources):
  - Jobs: `app/Filament/TitanSolo/Resources/JobResource.php` + pages
  - Customers: `app/Filament/TitanSolo/Resources/CustomerResource.php` + pages
  - Invoices: `app/Filament/TitanSolo/Resources/InvoiceResource.php` + pages
  - Included invoice record action to mark invoice paid.
- Enforced TitanSolo access in `app/Models/User.php`:
  - only `owner` role
  - only single-operator plan keys (`starter`, `solo`, `single_operator`)
- Added TitanSolo product switcher entry in `resources/js/components/AppSidebar.vue` with plan/role gating.
- Updated routing/access tests and added TitanSolo-focused tests:
  - `tests/Feature/PanelRoutingTest.php`
  - `tests/Feature/TitanSoloPanelTest.php`

## Validation Notes
- `php -l` passed for changed/new PHP files.
- Full/targeted Pest tests could not run in this environment because dependencies are unavailable under current PHP runtime.

## Next Steps
1. Install project dependencies in a PHP-compatible environment.
2. Run targeted tests:
   - `./vendor/bin/pest tests/Feature/PanelRoutingTest.php tests/Feature/TitanQuotesPanelTest.php tests/Feature/TitanSoloPanelTest.php`
3. Run full suite and linters before merge.
