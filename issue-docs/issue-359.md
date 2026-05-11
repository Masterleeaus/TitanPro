# Issue 359 — [FOLLOW-UP] Add platform-wide reporting widgets to TitanPro super-admin panel

## Files Changed

- `app/Filament/TitanPro/Widgets/ActiveOrganizationsWidget.php`
- `app/Filament/TitanPro/Widgets/PlatformRevenueWidget.php`
- `app/Filament/TitanPro/Widgets/UsageMetricsWidget.php`
- `app/Filament/TitanPro/Widgets/FailedJobsWidget.php`
- `app/Providers/Filament/TitanProPanelProvider.php`
- `tests/Feature/Admin/TitanProReportingWidgetsTest.php`

## Fixes Applied

- Added four TitanPro super-admin dashboard widgets for platform-wide observability:
  - active organizations with prior-period delta
  - estimated MRR and current-month revenue with prior-period deltas
  - total jobs, invoices, and payments across all organizations
  - failed job count and latest failure timestamp
- Registered all new widgets in `TitanProPanelProvider::panel()->widgets([...])`.
- Implemented cached aggregate queries (`Cache::remember` with 60s TTL) for each widget.
- Ensured aggregate queries are cross-tenant by using platform-level DB queries without `organization_id` scoping helpers.
- Added Pest coverage to verify super-admin widget rendering and expected aggregate metrics.

## Next Steps

- Run the new Pest test file and Filament dashboard route assertions in a PHP 8.4+ environment with Composer dependencies installed.
- Re-run CI to confirm production checks pass with the new TitanPro widgets.
