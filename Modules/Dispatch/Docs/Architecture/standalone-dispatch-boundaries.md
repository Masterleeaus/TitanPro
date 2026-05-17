# Standalone Dispatch Boundaries

Dispatch now owns its field-service scheduling domain without requiring Jobs, Bookings, or Invoice modules.

## Owned by Dispatch

- Work orders
- Appointments
- Shifts and shift assignments
- Technician profiles and skills
- Customer service locations
- Service zones
- Routes and route stops
- Status timeline logs

## Integration surface

External modules should integrate through contracts/events rather than direct model coupling:

- `DispatchSchedulerContract`
- `DispatchStatusContract`
- `WorkOrderScheduled`
- `DispatchStatusChanged`

## API boundary

API controllers validate through module-local form requests and return module-local resources. This allows future modules to call Dispatch without requiring Dispatch to depend on them.
