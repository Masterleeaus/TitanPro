# Cleaning-first Dispatch Upgrade

This pass keeps the standalone Dispatch module but narrows the operational product toward cleaning teams.

## Added

- Cleaning visit status enum.
- Cleaning service type enum with default durations.
- Today board service grouped by cleaner lanes.
- Cleaner workload summaries.
- Reminder due-list service.
- Check-in, complete, and missed-visit actions.
- Filament today board page and Blade view.
- Migration for service type, arrival windows, check-in/out, completion notes, and photo paths.

## Product boundary

This module remains standalone. External booking, invoice, or job modules can integrate later by creating `DispatchWorkOrder` and `DispatchAppointment` records or calling `DispatchSchedulerContract`.
