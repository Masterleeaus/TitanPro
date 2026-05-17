# Dispatch Standalone Hardening Pass 006

This pass keeps Dispatch independent from Jobs, Bookings, and Invoice modules while improving runtime safety.

## Added

- Schedule window DTO with duration and overlap helpers.
- Technician availability service for overlapping appointment checks.
- Schedule validator used by scheduling actions.
- Reschedule appointment action.
- Dispatch KPI service, reporting action, API resource, and API route.
- Dispatch health check console command.
- Unit tests for the schedule window value object.

## Corrected

- `DispatchSchedulerContract` now returns `DispatchWorkOrder`, matching the concrete scheduling action.
- `DispatchScheduler` now dispatches schedule events against the refreshed work order.
- `ModuleServiceProvider` now binds transition guard and schedule validation services.

## Boundary

No dependency on CleaningJobs, BookingModule, or EInvoice was reintroduced.
