# Issue 336 — [FOLLOW-UP] Add plan pricing / stripe_amount column to ZeroPay SubscriptionResource list

## Summary

Adds an org-scoped **Amount** column to the ZeroPay subscription list using
`PlanService::amountFor($plan, $billingInterval)`, formats it as currency, and
adds a matching amount filter for bookkeeper triage.

Source: Follows from `issue-docs/issue-197.md` (ZeroPay `SubscriptionResource`)

## Files Changed

| File | Change type | Description |
|------|-------------|-------------|
| `app/Services/PlanService.php` | modified | Added `amountFor()` so subscription list pricing can resolve a plan + billing interval into one display amount. |
| `app/Filament/ZeroPay/Resources/SubscriptionResource.php` | modified | Added the sortable/filterable **Amount** column, currency formatting with USD fallback, and eager loading for organization settings used by currency resolution. |
| `tests/Feature/Subscription/PlanServiceTest.php` | modified | Added focused assertions for `PlanService::amountFor()` across monthly and annual billing intervals. |
| `tests/Feature/Admin/ZeroPaySubscriptionResourceTest.php` | modified | Extended the ZeroPay subscription list coverage to assert the Amount column renders the expected formatted value. |
| `issue-docs/issue-336.md` | new | This issue implementation summary. |

## Fixes Applied

1. **Plan pricing lookup**
   - Added `PlanService::amountFor()` so list rendering uses a single canonical
     pricing lookup for `plan + billing_interval`.

2. **ZeroPay subscription list amount column**
   - Added an **Amount** column to `SubscriptionResource`.
   - Formats the value as currency and falls back to `USD` when no org currency
     setting is present.
   - Makes the derived amount sortable via a deterministic SQL `CASE`
     expression.
   - Adds an amount filter with plan/interval-backed options labelled by the
     formatted amount.

3. **Coverage**
   - Added focused PlanService tests for the new pricing method.
   - Updated the ZeroPay list feature test to assert the rendered amount text.

## Validation Notes

- `composer install --no-interaction --prefer-dist --no-progress` cannot
  complete on this runner because the lock file requires PHP 8.4 while the
  environment provides PHP 8.3.6.
- Local validation was therefore limited to static checks feasible without
  Composer-installed dependencies.

## Next Steps

1. Re-run the focused Pest tests in a PHP 8.4 environment with Composer
   dependencies installed:
   - `./vendor/bin/pest tests/Feature/Admin/ZeroPaySubscriptionResourceTest.php`
   - `./vendor/bin/pest tests/Feature/Subscription/PlanServiceTest.php`
2. If organization currency settings are added to persistent settings schema in
   a future pass, wire the same field into the existing currency fallback path.
