# BookingModule Acceptance Checklist

- Module installs without missing optional ProviderManagement or ZoneManagement modules.
- All migrations and seeders are idempotent.
- Legacy Worksuite sidebar remains available in `legacy` and `hybrid` modes.
- Filament classes are present but optional; non-Filament hosts do not crash.
- All writes flow through Actions or existing Services.
- Scheduled reminders are company-scoped and deduplicated.
- Booking lifecycle events are logged and can send queued mail.
- AI manifests and ModuleAgent policy are present for TitanCore/TitanAgents discovery.
