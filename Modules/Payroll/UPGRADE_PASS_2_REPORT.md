# Payroll Upgrade Pass 2

## Added

- Persistent payroll run, approval, and audit-log entities.
- Migrations for `payroll_runs`, `payroll_run_approvals`, and `payroll_audit_logs`.
- Payroll run repository contract and Eloquent implementation.
- Payroll approval service with submit/approve/reject transitions.
- Audit service for state-change evidence trails.
- Bank CSV exporter and adjustment CSV importer.
- API endpoints for health, approvals, rejection, and bank-file export.
- Approval notification event/listener surfaces.
- Filament overview widget and GraphQL schema/query stubs.
- Health check and extra unit/feature tests.

## Preserved

All original Payroll entities, controllers, routes, views, migrations, observers, exports, and existing Pass 1 engine files remain in place.
