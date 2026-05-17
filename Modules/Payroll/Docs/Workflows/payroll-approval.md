# Payroll Approval Workflow

1. Payroll run is generated in `draft`.
2. Payroll admin submits the run for approval.
3. Finance reviewer approves or rejects the run.
4. Approved runs can be exported to bank/accounting systems.
5. All state changes are written to `payroll_audit_logs`.
