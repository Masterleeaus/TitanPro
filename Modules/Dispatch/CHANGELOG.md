# Changelog

## 0.6.0 - Standalone dispatch hardening

- Added technician schedule conflict validation.
- Added appointment rescheduling action and API endpoint.
- Added dispatch KPI summary service and endpoint.
- Added health check command for required table verification.
- Fixed scheduler contract return type mismatch.


## 1.1.0

- Removed hard dependencies on previous external module dependencies.
- Added standalone dispatch work orders and dispatch appointments.
- Rewired scheduling, routing, API, Filament pages, and status logs to Dispatch-owned models.

## 1.0.0

- Initial Dispatch module scaffold and shift scheduling import.

## 0.4.0 - Dispatch standalone pass 004

- Added scheduler/status contracts for standalone integration points.
- Added core scheduler/status services and container bindings.
- Added domain events and timeline listener for scheduled work orders and assignment status changes.
- Added API form requests and JSON resources for stronger API boundaries.
- Added route recalculation queued job, in-app notification scaffold, and RBAC work-order policy.
- Confirmed removed Jobs, Bookings, and Invoice modules are not referenced by Dispatch.

## 0.5.0 - Dispatch standalone pass 005

- Added standalone work-order API create/list/show endpoints.
- Added calendar feed endpoint for dispatch board integrations.
- Added guarded assignment status transitions.
- Added Filament dashboard widgets and model factories.
- Removed stale manifest references to removed external modules.



## 0.7.0 - Dispatch standalone pass 007

### Added
- SLA policy, checklist, checklist item, and exception tables.
- SLA breach sweep console command.
- Checklist and exception Filament resources.
- Workflow documentation for SLA exception handling.

### Changed
- Work orders now expose checklist and exception relationships.
