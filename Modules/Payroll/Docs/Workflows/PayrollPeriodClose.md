# Payroll Period Close

Pass 6 adds payroll period locking so closed pay periods cannot be edited silently.

Flow:
1. Run payroll and complete approvals.
2. Export bank file, payslips, and accounting journal.
3. Lock the payroll period.
4. Any later correction requires an unlock reason and audit metadata.

API:
- `POST /api/payroll/period-locks/lock`
- `POST /api/payroll/period-locks/unlock`
- `POST /api/payroll/exports/journal`
- `POST /api/payroll/variance`
