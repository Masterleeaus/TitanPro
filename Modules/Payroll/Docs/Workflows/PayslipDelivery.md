# Payslip Delivery Workflow

When a payslip is generated, Payroll can immediately deliver an employee notification.

1. `GeneratePayslipsJob` receives calculation rows.
2. `GeneratePayslipAction` builds a `PayslipDocument`.
3. `PayslipDeliveryService` sends `PayslipCreatedNotification` to the employee email/notifiable.
4. Delivery status is returned in the payslip payload and logged for audit visibility.

## Configuration

```env
PAYROLL_SEND_PAYSLIPS_TO_EMPLOYEES=true
PAYROLL_QUEUE_PAYSLIP_DELIVERY=true
```

Notification channels are controlled by `Config/notifications.php` under `payslip_created`.
