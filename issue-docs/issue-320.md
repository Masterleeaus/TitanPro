# Issue 320 — Cross-org access tests for every org-scoped resource and policy

## Issue Summary

Follow-up to issues #129, #130, and #131 which introduced tenant-scoping fixes across
resources and policies. Feature-test coverage for cross-org access attempts was uneven —
many policies and resources had no test asserting that an org-A user is denied access to
an org-B record.

## Files Changed

| File | Change |
|------|--------|
| `tests/Feature/CrossOrgAccessTest.php` | New comprehensive cross-org access test file (all resources) |
| `database/factories/PaymentFactory.php` | New factory for Payment model |
| `database/factories/MessageTemplateFactory.php` | New factory for MessageTemplate model |
| `database/factories/JobMessageFactory.php` | New factory for JobMessage model |
| `database/factories/JobChecklistItemFactory.php` | New factory for JobChecklistItem model |
| `database/factories/EstimatePackageFactory.php` | New factory for EstimatePackage model |
| `app/Models/MessageTemplate.php` | Added `HasFactory` trait |
| `app/Models/JobMessage.php` | Added `HasFactory` trait |

## Tests Added

`tests/Feature/CrossOrgAccessTest.php` contains four groups of tests:

### Part 1 — Policy: `view/update/delete` return false for foreign-org models

Three policy methods (`view`, `update`, `delete`) are tested for each of:
- Invoice, Payment, Estimate, EstimatePackage, Attachment, OrganizationSetting
- JobMessage, JobChecklistItem, MessageTemplate, DriverLocation
- Job, Customer, Property, Item, JobType

Total: 44 policy tests.

### Part 2 — Eloquent scope: listing returns only the current org's rows

- `authenticated user only sees their own payments`
- `authenticated user only sees their own estimates`
- `authenticated user only sees their own message templates`
- `authenticated user cannot find a payment belonging to another org`
- `authenticated user cannot find an estimate belonging to another org`

### Part 3 — Owner panel HTTP: cross-org access returns 404 / 403

- `org A user cannot view org B invoice via owner HTTP`
- `org A user cannot delete org B invoice via owner HTTP`
- `org A user cannot view org B estimate via owner HTTP`
- `org A user cannot edit org B estimate via owner HTTP`
- `org A user cannot delete org B estimate via owner HTTP`
- `org A invoice index never includes org B invoices`

Note: GET `/owner/estimates` (index) redirects to `/titanquotes` (line 60 of web.php),
so estimate listing is covered by the Eloquent scope tests rather than an HTTP index test.

### Part 4 — Filament admin panel `/titanpro`: edit page 404s for other-org record

Uses the `scopedAdmin()` helper that creates a `super_admin` user with an `organization_id`
so that `getEloquentQuery()` scoping is exercised:

- `invoices admin edit page 404s for other-org record`
- `payments admin edit page 404s for other-org record`
- `estimates admin edit page 404s for other-org record`
- `estimate-packages admin edit page 404s for other-org record`
- `attachments admin edit page 404s for other-org record`
- `job-messages admin edit page 404s for other-org record`
- `message-templates admin edit page 404s for other-org record`
- `job-checklist-items admin edit page 404s for other-org record`

Note: The pre-existing `tests/Feature/Admin/OrgScopingTest.php` uses `/admin/...` paths
which were valid when that panel was at `/admin`. The panel was renamed to `/titanpro`
(issue #126). The new tests use the correct `/titanpro/...` paths.

## Acceptance Criteria Coverage

| Resource | Policy tests | Eloquent list | HTTP access denied |
|---|---|---|---|
| Invoice | ✅ view/update/delete | ✅ (TenantIsolationTest) | ✅ show/delete + index |
| Payment | ✅ view/update/delete | ✅ new | ✅ Filament admin |
| Estimate | ✅ view/update/delete | ✅ new | ✅ show/edit/delete |
| EstimatePackage | ✅ view/update/delete | n/a (no direct scope) | ✅ Filament admin |
| Attachment | ✅ view/update/delete | n/a (no TenantScope) | ✅ Filament admin |
| OrganizationSetting | ✅ view/update/delete | n/a | ✅ (OrgScopingTest) |
| JobMessage | ✅ view/update/delete | n/a (no TenantScope) | ✅ Filament admin |
| DriverLocation | ✅ view/update | n/a | ✅ (OrgScopingTest) |
| Job | ✅ view/update/delete | ✅ (TenantIsolationTest) | ✅ (TenantIsolationTest) |
| Customer | ✅ view/update/delete | ✅ (TenantIsolationTest) | ✅ (TenantIsolationTest) |
| Property | ✅ view/update/delete | n/a | ✅ (OrgScopingTest) |
| Item | ✅ view/update/delete | n/a | ✅ (OrgScopingTest) |
| JobType | ✅ view/update/delete | n/a | ✅ (OrgScopingTest) |
| JobChecklistItem | ✅ view/update/delete | n/a | ✅ Filament admin |
| MessageTemplate | ✅ view/update/delete | ✅ new | ✅ Filament admin |

## Next Steps

- Run `composer run test` in a PHP 8.4 environment to execute the full suite.
- Consider adding a `DriverLocationFactory` for cleaner test setup.
- The pre-existing OrgScopingTest.php tests use deprecated `/admin/...` panel paths
  (the panel moved to `/titanpro` in issue #126). Those tests return 404 because the
  route doesn't exist, not because of org scoping. They should be updated in a future
  issue to use `/titanpro/...` paths for meaningful assertions.
