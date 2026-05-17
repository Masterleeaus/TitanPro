# Issue 197 — [FOLLOW-UP] Remove duplicate InvoiceResource / PaymentResource from app/Filament/Resources

## Decision

Repurpose the TitanPro `InvoiceResource` and `PaymentResource` as **cross-tenant super-admin
views** instead of deleting them.

### Why this direction

- The TitanPro panel already links to these finance resources, so keeping them avoids breaking
  existing super-admin navigation.
- The resources were already super-admin-only; making them cross-tenant turns the fallback into
  a purposeful platform-ops view instead of a duplicate org-scoped finance surface.
- ZeroPay remains the authoritative finance panel for `bookkeeper`, `owner`, and `admin`.

## Files changed

| File | Action | Purpose |
|------|--------|---------|
| `app/Filament/Resources/InvoiceResource.php` | Modified | Removed tenant scoping from the TitanPro super-admin query and added an organization column so invoices are clearly cross-tenant. |
| `app/Filament/Resources/PaymentResource.php` | Modified | Removed tenant scoping from the TitanPro super-admin query and added an organization column so payments are clearly cross-tenant. |
| `tests/Feature/Admin/InvoicePaymentAccessTest.php` | Modified | Updated coverage for the repurposed TitanPro routes and added ZeroPay org-scoping checks for owner/admin/bookkeeper invoice access. |
| `tests/Feature/CrossOrgAccessTest.php` | Modified | Updated invoice/payment TitanPro edit-page expectations to reflect intentional cross-tenant super-admin access. |

## Fixes applied

1. **TitanPro InvoiceResource**
   - Removed the extra org filter and bypassed the model `TenantScope` in `getEloquentQuery()`.
   - Added an `organization.name` table column so super-admins can tell which tenant owns each invoice.

2. **TitanPro PaymentResource**
   - Removed the extra org filter and bypassed the model `TenantScope` in `getEloquentQuery()`.
   - Added an `organization.name` table column so super-admins can tell which tenant owns each payment.

3. **Access tests**
   - Kept non-super-admin TitanPro finance access forbidden.
   - Added cross-tenant TitanPro super-admin coverage for invoice/payment list + edit access.
   - Added ZeroPay invoice list assertions confirming owner/admin/bookkeeper still only see their own org’s invoices.

## Validation notes

- Attempted `composer install --no-interaction --prefer-dist --no-progress`, but the lock file
  requires PHP 8.4 packages while this runner has PHP 8.3.6, so Laravel/Pest validation could not
  be executed locally in this environment.

## Next steps

- Re-run the updated Pest targets in a PHP 8.4 environment:
  - `./vendor/bin/pest tests/Feature/Admin/InvoicePaymentAccessTest.php`
  - `./vendor/bin/pest tests/Feature/CrossOrgAccessTest.php`
- If route/action codegen is regenerated in CI, confirm the generated TitanPro finance helpers
  still match the intended cross-tenant super-admin behavior.
