# Payroll MVP Hardening

Pass 9 adds operational safety around the cleaners-first payroll MVP.

## Added

- Duplicate payroll-run guard for company/period combinations.
- Finalization readiness checks before a run is closed.
- Cleaner roster-vs-paid-hours reconciliation service.
- Employee payslip access token issuing and validation.
- Failed payslip delivery retry job and command.
- Payroll summary service for dashboards/reports.
- MVP diagnostics command.

## Commands

```bash
php artisan payroll:mvp-diagnostics
php artisan payroll:payslips:retry-failed
```

## API

- `POST /api/payroll/runs/inspect`
- `POST /api/payroll/runs/{run}/finalize`
- `POST /api/payroll/me/payslips/{payslip}/token`
- `POST /api/payroll/me/payslips/{payslip}/token/validate`
