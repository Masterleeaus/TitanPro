# Issue 121 — [PANEL] Install ZeroPay Filament panel at /zeropay

## Issue Summary

Installed the ZeroPay payments and invoicing Filament panel at `/zeropay`. The panel previously existed as a skeleton `ZeroPayPanelProvider` with no resources, pages, or widgets. The `/zeropay` path was not serving a static CMS page (that had been removed before this work), but the panel was completely empty — no resources, no widgets, no role restrictions beyond the default.

## Changes Made

### New Files

- `app/Filament/ZeroPay/Pages/Dashboard.php`
  Custom dashboard page for the ZeroPay panel. Wires up `FinanceOverviewWidget`.

- `app/Filament/ZeroPay/Pages/StripeSettings.php`
  Filament page for managing Stripe integration settings (publishable key, secret key, webhook secret) scoped to the authenticated user's organisation via `OrganizationSetting`.

- `app/Filament/ZeroPay/Widgets/FinanceOverviewWidget.php`
  Finance stats widget displaying: revenue this month, outstanding invoices balance, overdue invoice count, revenue this week, invoice status breakdown table, and five most recent payments.

- `app/Filament/ZeroPay/Resources/InvoiceResource.php`
  Invoice resource for the ZeroPay panel. Organisation-scoped via `getEloquentQuery()`.

- `app/Filament/ZeroPay/Resources/InvoiceResource/Pages/ListInvoices.php`
- `app/Filament/ZeroPay/Resources/InvoiceResource/Pages/CreateInvoice.php`
- `app/Filament/ZeroPay/Resources/InvoiceResource/Pages/EditInvoice.php`
  Standard CRUD pages for the ZeroPay invoice resource.

- `app/Filament/ZeroPay/Resources/PaymentResource.php`
  Payment resource for the ZeroPay panel. Organisation-scoped via `getEloquentQuery()`.

- `app/Filament/ZeroPay/Resources/PaymentResource/Pages/ListPayments.php`
- `app/Filament/ZeroPay/Resources/PaymentResource/Pages/CreatePayment.php`
- `app/Filament/ZeroPay/Resources/PaymentResource/Pages/EditPayment.php`
  Standard CRUD pages for the ZeroPay payment resource.

- `resources/views/filament/zeropay/widgets/finance-overview-widget.blade.php`
  Blade view for `FinanceOverviewWidget`.

- `resources/views/filament/zeropay/pages/stripe-settings.blade.php`
  Blade view for the Stripe settings page.

### Modified Files

- `app/Providers/Filament/ZeroPayPanelProvider.php`
  - Set brand name to `ZeroPay` (was `ZeroPay — Payments`)
  - Added explicit `pages([Dashboard::class, StripeSettings::class])`
  - Added `widgets([Widgets\AccountWidget::class, FinanceOverviewWidget::class])`
  - Added `discoverResources`, `discoverPages`, `discoverWidgets` pointing to `app/Filament/ZeroPay/`
  - Added doc comment explaining panel purpose and access roles

- `app/Models/User.php`
  - Updated `canAccessPanel()` to allow `bookkeeper` role to access the `zeropay` panel.
  - All other panels retain the original `super_admin`, `admin`, `owner` check.

- `routes/web.php`
  - Added `GET /zeropay-product` route pointing to `CmsPageController::show('zeropay')` as the product marketing page alias, to make the CMS content accessible under a non-conflicting path.

- `tests/Feature/PanelRoutingTest.php`
  - Added `zeropay panel is accessible to bookkeeper role` test asserting that a `bookkeeper` user can access `/zeropay`.

## Fixes Applied

- **Role restriction**: `User::canAccessPanel()` now grants `bookkeeper` role access to the ZeroPay panel (required for finance staff who are not full admins).
- **Empty panel**: ZeroPayPanelProvider now discovers and registers all ZeroPay resources, pages, and widgets.
- **CMS route conflict**: `/zeropay-product` alias added for the existing CMS ZeroPay marketing page; the Filament panel owns `/zeropay`.

## Next Steps

- Add `billing_subscription_management` resources (e.g. `SubscriptionResource`) to the ZeroPay panel once a Subscription model/resource exists.
- Consider adding a product-switcher navigation link from ZeroPay to other panels (`BezhanSalleh\PanelSwitch` plugin or custom navigation item).
- Run `composer run test` against the `PanelRoutingTest` in a PHP 8.4 environment to confirm the `bookkeeper` routing test passes.
- Consider restricting `InvoiceResource` and `PaymentResource` in the admin (`titanpro`) panel to super-admin only, now that they have a dedicated home in ZeroPay.
