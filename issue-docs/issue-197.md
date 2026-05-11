# Issue 197 — Restrict InvoiceResource / PaymentResource in TitanPro panel to super_admin only

## Context

Follows from issue-121 (ZeroPay panel).  The `InvoiceResource` and `PaymentResource` that live
in `app/Filament/Resources/` are auto-discovered by the TitanPro super-admin panel
(`/titanpro`).  An identical pair of resources also live in `app/Filament/ZeroPay/Resources/`
and are served by the ZeroPay panel (`/zeropay`) for `bookkeeper`, `owner`, and `admin` roles.

Having two panels expose the same financial data with different role gates leaks access: an
`admin` user can (in principle) edit invoices in the TitanPro panel even though the
authoritative finance surface is ZeroPay.

## Changes Made

### Modified Files

**`app/Filament/Resources/InvoiceResource.php`**
- Added `use Illuminate\Database\Eloquent\Model;` import.
- Added doc-block explaining the super-admin restriction and pointing operators to ZeroPay.
- Added the following authorization methods, each returning `true` only for users with the
  `super_admin` role:
  - `canViewAny()` — guards the list page
  - `canCreate()` — guards the create page
  - `canEdit(Model $record)` — guards the edit page
  - `canView(Model $record)` — guards the view/detail page
  - `canDelete(Model $record)` — guards individual record deletion
  - `canDeleteAny()` — guards bulk deletion

**`app/Filament/Resources/PaymentResource.php`**
- Same set of changes as `InvoiceResource` above.

### New Files

**`tests/Feature/Admin/InvoicePaymentAccessTest.php`**
- Confirms that `/admin/invoices` and `/admin/payments` (old alias paths) return 404 for an
  authenticated `admin` user (those routes are not registered).
- Confirms that `admin`, `bookkeeper`, and `owner` users receive 403 Forbidden when hitting
  `/titanpro/invoices` and `/titanpro/payments` (blocked by the panel-level gate in
  `User::canAccessPanel()`).

## Files NOT Changed

**`app/Filament/ZeroPay/Resources/InvoiceResource.php`**  
**`app/Filament/ZeroPay/Resources/PaymentResource.php`**  
These resources remain unrestricted at the resource level; access is gated by the ZeroPay
panel (`canAccessPanel()` allows `owner`, `admin`, `bookkeeper`).  No further changes needed.

## How Authorization Works

1. **Panel gate** — `User::canAccessPanel()` reads `config('titan_panels.panels.titanpro.roles')`
   which resolves to `['super_admin']`.  Non-super-admin users receive HTTP 403 before they can
   reach any resource inside the TitanPro panel.
2. **Resource gate** — The new `canViewAny()` / `canCreate()` / etc. methods on the TitanPro
   `InvoiceResource` and `PaymentResource` provide a second layer of defence: even if the panel
   gate were relaxed in future, these resources will remain visible only to `super_admin`.

## Next Steps

- Consider removing the duplicate `InvoiceResource` / `PaymentResource` from
  `app/Filament/Resources/` entirely if super-admin has no operational need to manage individual
  invoices (they have the ZeroPay panel available).
- If super-admin cross-tenant views are required, scope the TitanPro resources to show **all**
  organisations (remove the `organization_id` filter from `getEloquentQuery()`).
- Run `composer run test` after deploying to confirm all new tests pass.
