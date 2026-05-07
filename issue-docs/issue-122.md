## Issue Summary
ZeroFuss panel coverage was incomplete for issue #122: the panel needed an explicit customer-portal purpose, strict role-based access, a dedicated dashboard/resource scaffold, and verification that navigation and routing behavior match the intended `/zerofuss` product surface.

## Root Cause
Although a `ZeroFussPanelProvider` file already existed, implementation details were still misaligned with the issue contract: branding was not exactly `ZeroFuss`, panel access logic in `User::canAccessPanel()` was not panel-specific, customer role seeding was missing, and there were no dedicated ZeroFuss dashboard/resource classes or focused access tests for authorized vs unauthorized roles.

## Changes Made
- `app/Providers/Filament/ZeroFussPanelProvider.php`
  - Kept panel id/path as `zerofuss`.
  - Changed brand from `ZeroFuss — Customer Portal` to `ZeroFuss`.
  - Switched dashboard registration from generic `Pages\Dashboard::class` to dedicated `App\Filament\ZeroFuss\Pages\Dashboard::class`.
- `app/Filament/ZeroFuss/Pages/Dashboard.php`
  - Added initial ZeroFuss dashboard page class for the customer portal surface.
- `app/Filament/ZeroFuss/Resources/BookingResource.php`
  - Added initial core ZeroFuss resource (bookings) using tenant-scoped `Job` data.
  - Added read-only list behavior and org-scoped query guard.
- `app/Filament/ZeroFuss/Resources/BookingResource/Pages/ListBookings.php`
  - Added list page wiring for the ZeroFuss bookings resource.
- `app/Models/User.php`
  - Updated `canAccessPanel(Panel $panel)` to enforce per-panel roles from `config('titan_panels.panels.{id}.roles')`.
  - Preserved fallback for panels without config role metadata.
- `database/seeders/RolesAndPermissionsSeeder.php`
  - Added `customer` role creation for ZeroFuss persona access.
- `tests/Feature/PanelRoutingTest.php`
  - Updated canonical role expectation for `/zerofuss` from `owner` to `customer`.
- `tests/Feature/Admin/ZeroFussPanelAccessTest.php`
  - Added focused access tests: `customer` allowed; `owner` and `admin` forbidden.
- `resources/js/components/AppSidebar.vue`
  - Added ZeroFuss product-switcher style nav entry (`/zerofuss`) for `customer` role users.
- `tests/Feature/ZeroFussNavLinkTest.php`
  - Added checks for ZeroFuss panel registration metadata (id/path/label/roles).

## Tests Added or Updated
- Added `tests/Feature/Admin/ZeroFussPanelAccessTest.php`.
- Added `tests/Feature/ZeroFussNavLinkTest.php`.
- Updated `tests/Feature/PanelRoutingTest.php` (`/zerofuss` expected role changed to `customer`).
- Environment limitation: full Pest/lint execution is blocked in this sandbox because dependencies are not installed (`vendor/` and `node_modules/` missing) and PHP runtime is 8.3 while project requires `^8.4`.

## Next Steps
- Run `composer run test` in a PHP 8.4 environment with dependencies installed to validate all panel tests end-to-end.
- Run `npm run lint` (and optionally `npm run build`) after installing frontend dependencies.
- If customer accounts need stricter record ownership than org-level access, add user-to-customer binding and apply per-user row-level filtering in ZeroFuss resources.
