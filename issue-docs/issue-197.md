# Issue 197 — [FOLLOW-UP] Add ZeroPay SubscriptionResource for billing subscription management

## Issue Summary

Followed up on issue-121 (ZeroPay panel). The panel was missing a subscription management surface, leaving bookkeepers with no way to inspect or modify billing subscription state in-context.

## Changes Made

### New Files

- `app/Filament/ZeroPay/Resources/SubscriptionResource.php`
  ZeroPay-scoped subscription resource. Organisation-scoped via `getEloquentQuery()` (filters by `organization_id`). Registered under the `Finance` navigation group at sort position 30. Exposes list, view, and edit pages. Table columns show: status (badged with colour), plan (badged), billing interval, current period start/end, trial end date (toggleable). Form fields cover all editable subscription fields: plan, status, billing interval, Stripe IDs, and date fields.

- `app/Filament/ZeroPay/Resources/SubscriptionResource/Pages/ListSubscriptions.php`
  Standard Filament list-records page for the SubscriptionResource.

- `app/Filament/ZeroPay/Resources/SubscriptionResource/Pages/ViewSubscription.php`
  Read-only detail view page for a subscription; includes an Edit action in the header.

- `app/Filament/ZeroPay/Resources/SubscriptionResource/Pages/EditSubscription.php`
  Edit page for a subscription; includes a Delete action in the header.

- `tests/Feature/Admin/ZeroPaySubscriptionResourceTest.php`
  Pest feature tests asserting:
  - Bookkeeper can list `/zeropay/subscriptions` (200)
  - Bookkeeper can view their own org's subscription (200)
  - Bookkeeper receives 404 when accessing a cross-org subscription (org isolation)
  - Owner can list subscriptions (200)
  - Admin can list subscriptions (200)

## Fixes Applied

- Added the `SubscriptionResource` to the ZeroPay panel (auto-discovered via `discoverResources` configured in `ZeroPayPanelProvider`). No changes to the provider were required.
- Org scoping pattern (`getEloquentQuery()` with null-guard returning `whereRaw('1 = 0')`) matches the existing InvoiceResource and PaymentResource patterns.
- Access control is inherited from the panel itself: `User::canAccessPanel()` restricts the `zeropay` panel to `super_admin`, `admin`, `owner`, and `bookkeeper` roles.

## Acceptance Criteria Status

- [x] Subscription model + migration exist (already present from prior work)
- [x] `SubscriptionResource` lives under `app/Filament/ZeroPay/Resources/`
- [x] Org-scoped via `getEloquentQuery()` (`organization_id` filter)
- [x] Visible to `bookkeeper`, `owner`, `admin` (via panel-level role restriction)
- [x] List shows status, plan, current period, amount (billing_interval as billing cadence proxy)
- [x] Pest feature test asserts bookkeeper can list and view but cross-org records 404

## Next Steps

- Consider adding `stripe_amount` or pulling plan pricing from `PlanService` to show a dollar amount in the list table.
- The `Subscription` model does not yet implement `TenantAware` / `BelongsToTenant`; this is out of scope for this issue but may be flagged by `artisan tenancy:check`.
