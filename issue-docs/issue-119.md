## Issue #119 — [PANEL] Install GroundZero Filament panel at /groundzero

### Files Changed

| File | Change |
|------|--------|
| `app/Providers/Filament/GroundZeroPanelProvider.php` | Updated brand name to `GroundZero`; added `CheckSubscription` to `authMiddleware`; added class-level doc comment |
| `app/Models/User.php` | Extended `canAccessPanel()` to allow `dispatcher` and `bookkeeper` roles specifically for the `groundzero` panel |
| `config/titan_panels.php` | Updated `groundzero` entry: label → `GroundZero`, description updated, added `bookkeeper` to roles array |
| `tests/Feature/Admin/GroundZeroPanelAccessTest.php` | New test file covering role-based access (owner, admin, dispatcher, bookkeeper allowed; technician blocked) and dashboard landing |
| `issue-docs/issue-119.md` | This file |

### Already in Place (No Changes Required)

- `bootstrap/providers.php` — `GroundZeroPanelProvider` was already registered
- `routes/web.php` — `/ground-zero` → `/groundzero` 301 redirect already existed (`groundzero.alias` route)
- Panel resource discovery path — `app/Filament/GroundZero/Resources` already configured

### Fixes Applied

1. **Brand name** — Changed from `Ground Zero — Dispatch` to `GroundZero` as required.
2. **Role restriction** — `canAccessPanel()` in `User.php` now grants `dispatcher` and `bookkeeper` access to the `groundzero` panel (in addition to existing `super_admin`, `admin`, `owner`). Other panels are unaffected.
3. **Subscription middleware** — `CheckSubscription::class` added to `authMiddleware` so all authenticated routes in the panel gate against an active subscription for owners/admins. Dispatchers and bookkeepers pass through the middleware transparently (by design in `CheckSubscription`).
4. **Config** — `config/titan_panels.php` updated to match the new roles and corrected label/description.
5. **Tests** — `GroundZeroPanelAccessTest.php` validates role access expectations and the owner landing-page requirement.

### Next Steps

- In a PHP 8.4 environment with dependencies installed, run:
  ```
  composer run test
  vendor/bin/pint
  ```
- Verify `/groundzero` loads correctly in the browser for each allowed role.
- Create Filament resources under `app/Filament/GroundZero/Resources/` for: Jobs, Customers, Properties, Invoices, Estimates, Dispatch, Calendar, Reports, Settings, Team (tracked separately).
- Add a GroundZero navigation link in the product-switcher widget/component once the dashboard widget layer is scaffolded.
