# Payroll Upgrade Pass 4

## Focus

Payslip delivery to employees after payslip creation.

## Added

- Payslip delivery service contract and implementation.
- Queued mail/database notification for created payslips.
- Standalone queue job for sending existing payslip documents.
- Automatic delivery from `GeneratePayslipAction` and `GeneratePayslipsJob`.
- Feature flags and notification channel config.
- Delivery DTO, unit tests, and workflow documentation.

## Preserved

Existing Payroll source files, migrations, routes, entities, reports, and pass 1-3 upgrades were preserved.
