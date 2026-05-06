# BookingModule

BookingModule owns booking intake, scheduling, assignment, dispatch, reminders, booking pages and booking lifecycle automation.

Blueprint alignment:
- **Actions** own write entrypoints.
- **Services** orchestrate domain logic.
- **Queries / ViewModels / Presenters** compose reads.
- **Events / Listeners / Jobs / Mail / Notifications** drive async lifecycle automation.
- **Knowledge / Agents / AI manifests** describe module-local AI context.
- **Filament / UI manifests** are optional operator surfaces and call module logic instead of duplicating it.
