# Issue 198 — GroundZero and ZeroPay product-switcher entries

## Issue Summary
Added missing product-switcher navigation entries for GroundZero and ZeroPay so users with access can jump between panels from the app sidebar.

## Files Changed
- `resources/js/components/AppSidebar.vue`
- `tests/Feature/ProductSwitcherPanelsTest.php`
- `issue-docs/issue-198.md`

## Fixes Applied
- Added `GroundZero Panel` switcher entry (`/groundzero`) in `AppSidebar.vue`.
- Added `ZeroPay Panel` switcher entry (`/zeropay`) in `AppSidebar.vue`.
- Added role gates for each entry to match panel access roles:
  - GroundZero: `owner`, `admin`, `dispatcher`, `bookkeeper`
  - ZeroPay: `owner`, `admin`, `bookkeeper`
- Kept visual parity with existing switcher items by using the same `mainNavItems` shape and rendering path through `NavMain`.
- Added focused feature tests asserting:
  - both new switcher links exist in `AppSidebar.vue`
  - panel role metadata matches the intended gating
  - active-state wiring continues to use `urlIsActive(item.href, page.url)` in `NavMain`

## Next Steps
- Run the full PHP/Pest suite in a PHP 8.4 environment with Composer dependencies installed to verify end-to-end behavior.