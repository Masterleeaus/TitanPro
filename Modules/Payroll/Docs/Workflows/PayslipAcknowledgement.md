# Payslip Delivery, Access, and Acknowledgement

Pass 5 adds an employee-facing delivery lifecycle:

1. Payslip is generated.
2. Delivery attempt is recorded in `payroll_payslip_deliveries`.
3. A secure signed download URL is attached when secure links are enabled.
4. Notification is sent through employee preferences.
5. Employee acknowledgement is recorded via `/api/payroll/payslips/deliveries/{delivery}/acknowledge`.
6. Failed/queued deliveries can be listed with `payroll:payslips:resend-failed`.

This keeps payroll auditable without requiring the Timesheet module.
