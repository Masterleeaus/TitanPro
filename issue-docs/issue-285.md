# Issue 285 — HandleInertiaRequests missing shared props required by frontend TypeScript types

## Issue Summary

`app/Http/Middleware/HandleInertiaRequests.php` did not share the `name`, `quote`, or `sidebarOpen`
props that the TypeScript interface `AppPageProps` (in `resources/js/types/index.d.ts`) declares as
required. Any Vue page reading `$page.props.name`, `$page.props.quote`, or `$page.props.sidebarOpen`
would receive `undefined` at runtime, causing silent JS errors or a broken UI.

## Files Changed

### Modified files

| File | Changes |
|------|---------|
| `app/Http/Middleware/HandleInertiaRequests.php` | Added `name`, `quote`, and `sidebarOpen` to the `share()` return array. |

## Fixes Applied

| Prop | Value | Notes |
|------|-------|-------|
| `name` | `config('app.name')` | Application name from Laravel config. |
| `quote` | `['message' => '', 'author' => '']` | Static empty default; can be wired to a DB table or config in a follow-up. |
| `sidebarOpen` | `$request->cookie('sidebar_state') === 'true'` | Reads the unencrypted `sidebar_state` cookie (excluded from `encryptCookies` in `bootstrap/app.php`); defaults to `false` when the cookie is absent. |

All pre-existing props (`auth`, `subscription`, `plan`, `platform`) are unchanged.

## Acceptance Criteria Verified

- [x] Middleware shares `name`, `quote`, and `sidebarOpen`
- [x] TypeScript types remain in sync — `AppPageProps` already declared all three; no TS changes needed
- [x] `sidebarOpen` is driven by the persisted `sidebar_state` cookie so sidebar state survives page reloads
- [x] Existing Inertia pages still receive `auth`, `subscription`, `plan`, `platform`

## Next Steps

1. **Quote service** — if rotating motivational quotes are desired, create a `quotes` config file or
   a `Quote` model and replace the static default with a call to a `QuoteService::random()` helper.
   Track in a follow-up issue.
2. **User-preference sidebar state** — consider storing sidebar preference in the `user_preferences`
   table rather than a cookie so it roams across browsers/devices.
