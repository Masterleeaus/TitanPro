# Issue 197 — Build GroundZero panel Filament resources

## Issue Summary

The `/groundzero` Filament panel was scaffolded in issue-119 with panel routing, role gating,
and resource discovery, but contained no resources under `app/Filament/GroundZero/Resources/`.
This issue builds the complete GroundZero resource layer: Jobs, Customers, Properties, Invoices,
Estimates, Dispatch Board, Calendar, Reports, Settings, and Team.

## Files Changed

### New Resources

| File | Purpose |
|------|---------|
| `app/Filament/GroundZero/Resources/JobResource.php` | Full job management with org-scoped query |
| `app/Filament/GroundZero/Resources/JobResource/Pages/ListJobs.php` | Job list page |
| `app/Filament/GroundZero/Resources/JobResource/Pages/CreateJob.php` | Create job + org_id injection |
| `app/Filament/GroundZero/Resources/JobResource/Pages/EditJob.php` | Edit job with workflow validation |
| `app/Filament/GroundZero/Resources/JobResource/Pages/ViewJob.php` | View job detail |
| `app/Filament/GroundZero/Resources/CustomerResource.php` | Customer management, org-scoped |
| `app/Filament/GroundZero/Resources/CustomerResource/Pages/ListCustomers.php` | Customer list |
| `app/Filament/GroundZero/Resources/CustomerResource/Pages/CreateCustomer.php` | Create customer |
| `app/Filament/GroundZero/Resources/CustomerResource/Pages/EditCustomer.php` | Edit customer |
| `app/Filament/GroundZero/Resources/PropertyResource.php` | Property management, org-scoped |
| `app/Filament/GroundZero/Resources/PropertyResource/Pages/ListProperties.php` | Property list |
| `app/Filament/GroundZero/Resources/PropertyResource/Pages/CreateProperty.php` | Create property |
| `app/Filament/GroundZero/Resources/PropertyResource/Pages/EditProperty.php` | Edit property |
| `app/Filament/GroundZero/Resources/InvoiceResource.php` | Invoice management, org-scoped |
| `app/Filament/GroundZero/Resources/InvoiceResource/Pages/ListInvoices.php` | Invoice list |
| `app/Filament/GroundZero/Resources/InvoiceResource/Pages/CreateInvoice.php` | Create invoice |
| `app/Filament/GroundZero/Resources/InvoiceResource/Pages/EditInvoice.php` | Edit invoice |
| `app/Filament/GroundZero/Resources/EstimateResource.php` | Estimate management, org-scoped |
| `app/Filament/GroundZero/Resources/EstimateResource/Pages/ListEstimates.php` | Estimate list |
| `app/Filament/GroundZero/Resources/EstimateResource/Pages/CreateEstimate.php` | Create estimate |
| `app/Filament/GroundZero/Resources/EstimateResource/Pages/EditEstimate.php` | Edit estimate |
| `app/Filament/GroundZero/Resources/TeamResource.php` | Technician roster, org-scoped, read-only, slug=team |
| `app/Filament/GroundZero/Resources/TeamResource/Pages/ListTeam.php` | Team list |

### New Custom Pages

| File | Purpose |
|------|---------|
| `app/Filament/GroundZero/Pages/DispatchBoard.php` | Live job board / driver assignment overview |
| `app/Filament/GroundZero/Pages/CalendarPage.php` | 30-day job scheduling calendar view |
| `app/Filament/GroundZero/Pages/ReportsPage.php` | Operational reports (jobs, revenue, quotes) |
| `app/Filament/GroundZero/Pages/SettingsPage.php` | Panel-scoped org settings (name/phone/email/tax rate) |

### New Blade Views

| File | Purpose |
|------|---------|
| `resources/views/filament/groundzero/pages/dispatch-board.blade.php` | Dispatch board UI |
| `resources/views/filament/groundzero/pages/calendar.blade.php` | Calendar UI |
| `resources/views/filament/groundzero/pages/reports.blade.php` | Reports UI |
| `resources/views/filament/groundzero/pages/settings.blade.php` | Settings form UI |

### Tests

| File | Purpose |
|------|---------|
| `tests/Feature/GroundZero/GroundZeroResourcesTest.php` | Route accessibility + tenant scoping tests |

## Fixes Applied

### Tenant scoping pattern
All five CRUD resources use the null-safe `getEloquentQuery()` guard pattern already established
across the codebase (e.g. `app/Filament/Resources/InvoiceResource.php`):

```php
public static function getEloquentQuery(): Builder
{
    $organizationId = auth()->user()?->organization_id;

    if ($organizationId === null) {
        return parent::getEloquentQuery()->whereRaw('1 = 0');
    }

    return parent::getEloquentQuery()
        ->where('organization_id', $organizationId);
}
```

When `organization_id` is null (no authenticated user or super-admin with no org context), the
query returns an empty result set — never leaking cross-org records.

### TeamResource slug
`TeamResource` uses model `User`. Without an explicit `$slug`, Filament would derive the URL
from the plural model name (`users`). A `$slug = 'team'` is declared to give it a clean, panel-
appropriate URL at `/groundzero/team`.

### Create page organization injection
All create pages inject `organization_id` via `mutateFormDataBeforeCreate()`, consistent with
the existing admin panel resources.

### SettingsPage fields
Settings form uses the correct `OrganizationSetting` column names (`company_name`, `company_phone`,
`company_email`, `company_address`, `default_tax_rate`) rather than non-existent `business_*`
or `*_prefix` columns.

## Next Steps

- Add role-level visibility guards to restrict certain resources to specific roles within the
  panel (e.g. bookkeeper sees invoices/estimates only; dispatcher sees jobs/dispatch/calendar).
- Wire up the DispatchBoard to real-time Livewire polling for live job status updates.
- Implement Calendar as a proper calendar grid using a Filament calendar plugin or custom
  Livewire component once a calendar library is available.
- Add invoice line-item management via Filament relation managers on InvoiceResource.
- Add estimate line-item management via Filament relation managers on EstimateResource.
- Consider adding a `TeamResource` create/invite flow once a user-invitation system is built.
