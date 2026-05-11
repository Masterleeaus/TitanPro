# Issue 197 — [FOLLOW-UP] Verify: GroundZero /groundzero UI for owner, admin, dispatcher, bookkeeper roles

## Issue Summary

Follow-up to issue #119. Verifies that the GroundZero Filament panel at `/groundzero` behaves correctly
for each allowed role (owner, admin, dispatcher, bookkeeper) and that disallowed roles are blocked.
Also verifies subscription gating via `CheckSubscription` middleware and the `GroundZero` brand name.

## Files Changed

| File | Change |
|------|--------|
| `tests/Feature/Admin/GroundZeroPanelAccessTest.php` | Extended with brand-name assertion, subscription gating tests (owner/admin blocked; dispatcher/bookkeeper pass-through), and section comments |
| `issue-docs/issue-197.md` | This file |

## Already in Place (No Code Changes Required)

- `app/Providers/Filament/GroundZeroPanelProvider.php` — `CheckSubscription` in `authMiddleware`; brand name set to `'GroundZero'`
- `app/Models/User.php` — `canAccessPanel()` grants `dispatcher` and `bookkeeper` roles for `groundzero` panel
- `routes/web.php` — `/ground-zero` → `/groundzero` 301 redirect (`groundzero.alias` route)
- `config/titan_panels.php` — `groundzero` roles list includes `dispatcher` and `bookkeeper`
- `tests/Feature/PanelRoutingTest.php` — `legacy_panel_aliases` dataset covers `/ground-zero` → `/groundzero` 301 redirect

## Fixes Applied

1. **Subscription gating tests** — Added tests that verify:
   - An `owner` with no active subscription is redirected to `route('owner.subscription.expired')` when accessing `/groundzero`.
   - An `admin` with no active subscription is redirected similarly.
   - A `dispatcher` passes through `CheckSubscription` even without an active subscription.
   - A `bookkeeper` passes through `CheckSubscription` even without an active subscription.

2. **Brand name test** — Added a test that follows redirects into `/groundzero` and asserts the rendered HTML contains `GroundZero`, confirming `OrganizationBrandingResolver::panelName('GroundZero')` resolves correctly.

## Acceptance Criteria Mapping

| Criterion | Test / Verification |
|-----------|---------------------|
| `/groundzero` loads for owner with active subscription | `owner can access the GroundZero panel` |
| `/groundzero` loads for admin with active subscription | `admin can access the GroundZero panel` |
| `/groundzero` loads for dispatcher (passes through CheckSubscription) | `dispatcher can access the GroundZero panel` + `dispatcher passes through CheckSubscription even without an active subscription` |
| `/groundzero` loads for bookkeeper (passes through CheckSubscription) | `bookkeeper can access the GroundZero panel` + `bookkeeper passes through CheckSubscription even without an active subscription` |
| `/groundzero` is blocked for technician | `technician cannot access the GroundZero panel` |
| `/ground-zero` 301-redirects to `/groundzero` | `legacy panel aliases permanently redirect to canonical routes` in `PanelRoutingTest.php` |
| Owner/admin without active subscription is gated | `owner without an active subscription is redirected by CheckSubscription` + `admin without an active subscription is redirected by CheckSubscription` |
| Brand name renders as `GroundZero` | `GroundZero panel renders brand name as GroundZero` |

## Next Steps

- Run `composer run test` (PHP 8.4 environment required) to confirm all tests pass.
- Run `vendor/bin/pint` to apply code style fixes.
