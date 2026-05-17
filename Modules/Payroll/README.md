# Payroll Module

Full Payroll module skeleton generated from the supplied module blueprint. Existing Payroll implementation files were preserved.

## Scope

- Salary components, salary groups, slips, payroll cycles, overtime, tax/tds, expenses integration, approvals, audit, reporting, AI, workflow, billing, registry, and integrations scaffolds.

## Notes

- Empty blueprint directories include `.gitkeep` so the ZIP preserves the full structure.
- Existing module routes, migrations, entities, providers, observers, resources, and data tables were not replaced.


## Upgrade Pass 3

- Compliance inspection service and API endpoint.
- Configurable payroll tax estimator with tax-band DTOs.
- Payslip HTML/PDF-ready rendering service and preview endpoint.
- Reconciliation service for bank/payment/provider comparisons.
- Reporting CSV/summary service.
- Diagnostics command and queued payslip generation job.
