## Issue Summary
Module policies under `Modules/*/Policies/` were audited for missing tenant-ownership checks. Several instance-level policy methods still relied on permission checks alone, which could allow cross-tenant record access when model-level ownership was not validated before authorization.

## Root Cause
The module policies had not consistently adopted the deny-first ownership guard pattern introduced in core `app/Policies/` by issue #130. In affected modules, policy methods called permission checks (for example, `$user->can(...)` / `hasPermissionTo(...)`) without verifying the target record belonged to the same tenant context.

## Changes Made
- Reviewed all policies under `Modules/*/Policies/`:
  - `Modules/BookingModule/Policies/AppointmentPolicy.php`
  - `Modules/BookingModule/Policies/SchedulePolicy.php`
  - `Modules/CleaningJobs/Policies/WorkOrderPolicy.php`
  - `Modules/CleanQuality/Policies/InspectionPolicy.php`
  - `Modules/CleanQuality/Policies/QcRecordPolicy.php`
  - `Modules/CleanQuality/Policies/TemplatePolicy.php`
  - `Modules/CleanQuality/Policies/SchedulePolicy.php`
  - `Modules/CRMCore/Policies/CRMCoreActivityLogPolicy.php`
  - `Modules/CRMCore/Policies/LeadPolicy.php`
  - `Modules/CRMCore/Policies/CRMCorePolicy.php`
  - `Modules/CRMCore/Policies/DealPolicy.php`
  - `Modules/SupplyChain/Policies/PurchaseOrderPolicy.php`
  - `Modules/SupplyChain/Policies/SupplierPolicy.php`
  - `Modules/SupplyChain/Policies/StockItemPolicy.php`
  - `Modules/TitanCore/Policies/TitanCorePolicy.php`
  - `Modules/TitanEchoAssist/Policies/ChatbotPolicy.php`
  - `Modules/TitanNexus/Policies/LeadPolicy.php`
  - `Modules/TitanNexus/Policies/CampaignPolicy.php`
  - `Modules/TitanNexus/Policies/LeadRecordPolicy.php`
  - `Modules/TitanZero/Policies/TitanZeroPolicy.php`
  - `Modules/CallingAgent/Policies/CallingAgentPolicy.php`
- Updated `Modules/BookingModule/Policies/AppointmentPolicy.php`
  - Before: `assign()` only checked module permission.
  - After: `assign()` now denies when `Appointment.company_id` does not match the user tenant id (`company_id` fallback to `organization_id`), then checks permission.
- Updated `Modules/CleanQuality/Policies/InspectionPolicy.php`
  - Before: `view/update/delete` only checked permissions.
  - After: `view/update/delete` now deny on cross-tenant `company_id` mismatch before permission checks.
- Updated `Modules/CleanQuality/Policies/QcRecordPolicy.php`
  - Before: `view/update/delete` only checked permissions.
  - After: `view/update/delete` now deny on cross-tenant `company_id` mismatch before permission checks.
- Updated `Modules/CRMCore/Policies/CRMCoreActivityLogPolicy.php`
  - Before: instance methods (`view/update/delete/restore/forceDelete/replicate`) only checked abilities.
  - After: those methods now deny when `company_id` does not match the user tenant id (`company_id` fallback to `organization_id`) before ability checks.
- Updated `Modules/CRMCore/Policies/DealPolicy.php`
  - Before: instance methods only checked abilities.
  - After: instance methods now call `belongsToOrganization()`:
    - if `crmcore_customer_id` is present, enforce related `crmcoreCustomer.organization_id === user.organization_id` (indirect parent ownership check);
    - otherwise enforce direct `company_id` match against the user tenant id (`company_id` fallback to `organization_id`).
- Updated `Modules/CRMCore/Policies/LeadPolicy.php`
  - Before: instance methods only checked abilities.
  - After: instance methods now call `belongsToOrganization()`, which enforces related `crmcoreCustomer.organization_id === user.organization_id` whenever `crmcore_customer_id` is present (indirect parent ownership check).
- Added `tests/Feature/Modules/ModulePolicyOwnershipTest.php`
  - Added cross-tenant denial tests for Booking appointment assignment, CleanQuality QC record update, and CRMCore deal view via related customer ownership.

## Tests Added or Updated
- Added `tests/Feature/Modules/ModulePolicyOwnershipTest.php` with:
  - `booking appointment assign denies cross-organization record`
  - `clean quality qc record update denies cross-organization record`
  - `crmcore deal view denies access when related customer belongs to another organization`
- Validation run notes in this environment:
  - `php -l` passed for all changed PHP files.
  - `npm run lint` fails pre-existing on module config files using `require()` (`Modules/Accountings/vite.config.js`, `Modules/BookingModule/webpack.mix.js`, etc.).
  - `npm run build` and `composer run test` cannot complete because PHP dependencies cannot be installed here (`composer.json` requires PHP `^8.4`, runtime is PHP `8.3.6`).

## Next Steps
- Add equivalent cross-tenant policy tests for additional frequently used module models (for example CRMCore `LeadPolicy` and activity-log policy paths).
- Consider normalizing module tenancy keys (`company_id`/`tenant_id`) toward a single org boundary key to simplify and harden authorization checks.
