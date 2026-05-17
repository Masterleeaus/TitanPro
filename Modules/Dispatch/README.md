# Dispatch Module

Standalone field-service dispatch module for Laravel/Filament.

## Owns

- Dispatch work orders
- Dispatch appointments
- Shift templates and assignments
- Technician profiles and skills
- Service zones and customer locations
- Route planning and route stops
- Status logs and lifecycle tracking

## Status

This pass removes hard dependencies on previous external module dependencies. Dispatch now runs as its own bounded module and may integrate with other modules later through contracts/events.


## Standalone API Surface

Dispatch now owns its own work-order intake, scheduling lifecycle, calendar feed, route building, technician matching, and guarded status transitions. Optional integrations should publish into Dispatch through contracts/events instead of requiring Jobs, Booking, or Invoice modules.


## Standalone Scheduling Safety

Dispatch now validates schedule windows before creating or rescheduling appointments. The module rejects overlapping active appointments for the same technician and exposes KPI and health-check utilities:

```bash
php artisan dispatch:health-check
```

API additions:

- `GET /api/v1/kpis`
- `PATCH /api/v1/appointments/{appointment}/reschedule`



## Pass 007 additions

- SLA policies by priority
- SLA breach sweep command
- Dispatch exception tracking
- Work-order checklists and checklist items
- Filament resources for checklists and exceptions
- Notification routing service for assigned technicians


## Cleaning-first MVP upgrade

The module now includes cleaner lane board services, check-in/completion actions, reminder due lists, cleaner workload summaries, and cleaning-specific visit/service enums.
