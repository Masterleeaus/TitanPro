# Payroll Upgrade Pass 6

Added production close-cycle controls:

- Payroll period lock/unlock service, entity, migration, and API.
- Accounting journal export service with JSON/CSV output.
- Payroll variance/anomaly comparison service and API.
- Fixed payslip secure-link and delivery services to match the actual `PayslipDocument` DTO shape.
- Added period-close workflow documentation and tests.

This pass makes Payroll safer for month-end close, accounting handoff, and post-close correction governance.
