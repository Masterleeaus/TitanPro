# Cleaners Payroll MVP Hardening

This pass preserves the full Payroll module and adds final MVP production checks for cleaning teams.

## Added

- Cleaner payroll configuration (`Config/cleaners.php`)
- Payroll finalisation guard
- Cleaner shift/site reconciliation service
- Cleaner payroll summary service and API controller
- Integrity diagnostic command
- Unit tests for reconciliation and finalisation guard

## Launch Focus

This keeps the module focused on cleaning businesses: weekly payroll, site-linked shifts, loadings, allowances, payslip delivery, reconciliation, and safer finalisation.
