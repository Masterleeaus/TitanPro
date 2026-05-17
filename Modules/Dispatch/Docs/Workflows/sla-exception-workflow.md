# SLA and Exception Workflow

Dispatch pass 007 adds standalone SLA and field-exception infrastructure.

## Flow

1. `dispatch_sla_policies` defines response and completion windows by priority.
2. `DispatchSlaService` calculates due times from `scheduled_for`.
3. `dispatch:sla-sweep` scans open work orders and raises `sla_breach` exceptions.
4. Dispatchers resolve or acknowledge exceptions from the Filament Exceptions resource.
5. Checklists attach site completion controls directly to work orders.

## Tables

- `dispatch_sla_policies`
- `dispatch_checklists`
- `dispatch_checklist_items`
- `dispatch_exceptions`

## Command

```bash
php artisan dispatch:sla-sweep
php artisan dispatch:sla-sweep --company_id=1
```
