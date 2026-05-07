# Issue 126 — [PANEL] Migrate TitanPro from /admin generic panel to dedicated /titanpro super-admin panel

## Issue Summary

Migrated the generic `/admin` Filament panel to a dedicated `/titanpro` super-admin panel.
The new `TitanProPanelProvider` is super-admin-only; the old `AdminPanelProvider` has been
converted to a no-op stub and removed from the provider registry.

## Root Cause

The only Filament panel was a generic `AdminPanelProvider` that (despite already using
`->id('titanpro')` and `->path('titanpro')`) was named `AdminPanelProvider` and permitted
access to the `admin`, `owner`, and `super_admin` roles. This conflated the super-admin
SaaS control surface with the per-tenant operator panel.

## Changes Made

### New Files

- **`app/Providers/Filament/TitanProPanelProvider.php`** — Dedicated PanelProvider for the
  `/titanpro` super-admin panel. Registers panel ID `titanpro` at path `titanpro`, brand name
  `TitanPro — Super Admin`, discovers all resources/pages/widgets from `app/Filament/`, and
  loads the same optional plugins as the former `AdminPanelProvider`.

### Modified Files

- **`app/Providers/Filament/AdminPanelProvider.php`** — Repurposed as a no-op
  `ServiceProvider` stub (no longer a `PanelProvider`). Retains the class so that any
  deployment scripts or IDE caches referencing the class name do not error.

- **`bootstrap/providers.php`** — Replaced `AdminPanelProvider::class` with
  `TitanProPanelProvider::class` so the dedicated panel is the one that boots.

- **`app/Models/User.php`** — Updated `canAccessPanel()` to gate the `titanpro` panel to
  the `super_admin` role exclusively. All other panels retain their existing role lists.

- **`tests/Feature/Admin/AdminAccessTest.php`** — Rewrote to match the new access model:
  - Removed stale tests for `/admin` and `/admin/login` (the panel is now at `/titanpro`).
  - Asserts the `/admin` → `/titanpro` 301 alias is still in place.
  - Asserts `super_admin` receives 200 on `/titanpro`.
  - Asserts `admin`, `owner`, `dispatcher`, `technician`, `bookkeeper`, and no-role users
    all receive 403 on `/titanpro`.

### Pre-existing (no change required)

- **`config/titan_panels.php`** — `titanpro` entry with `'roles' => ['super_admin']` was
  already correct.
- **`routes/web.php`** — `Route::redirect('/admin', '/titanpro', 301)` was already in place.
- **`tests/Feature/PanelRoutingTest.php`** — Already lists `/titanpro` with `super_admin`
  role and includes `/admin` → `/titanpro` in `legacy_panel_aliases`.

## Fixes Applied

| Requirement | Status |
|---|---|
| `TitanProPanelProvider.php` created | ✅ |
| Panel registered at path `titanpro` with id `titanpro` | ✅ |
| Brand name `TitanPro — Super Admin` | ✅ |
| Access restricted to `super_admin` role only | ✅ |
| Resources discovered (Organization, User, etc.) via `discoverResources` | ✅ |
| Registered in `bootstrap/providers.php` | ✅ |
| `AdminPanelProvider` repurposed (no longer default catch-all) | ✅ |
| Test: `super_admin` can access `/titanpro` | ✅ |
| Test: `owner` role receives 403 on `/titanpro` | ✅ |

## Next Steps

- Add dedicated Filament resources under `app/Filament/TitanPro/` (Organisation management,
  cross-org User management, Subscription/billing oversight, Module management, Platform
  health dashboard) to replace the generic resources currently discovered from
  `app/Filament/Resources/`.
- Add a cross-panel navigation launcher widget (links to all 9 product panel routes) using
  the `config/titan_panels.php` registry.
- Add platform-wide reporting widgets (active orgs, revenue, usage metrics, failed jobs).
- Run `composer run test` in a PHP 8.4 environment with vendor dependencies installed to
  confirm all tests pass (this sandbox uses PHP 8.3 and vendor/ is not available).
- Consider whether the `->default()` panel designation on `TitanProPanelProvider` is correct
  for the deployment (it controls which panel handles unauthenticated root-level requests).
