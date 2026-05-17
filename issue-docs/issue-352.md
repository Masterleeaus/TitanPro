# Issue 352 — Install TitanRewind module

## Files changed

- `Modules/TitanRewind/module.json`
- `Modules/TitanRewind/Config/config.php`
- `Modules/TitanRewind/Providers/TitanRewindServiceProvider.php`
- `Modules/TitanRewind/Models/RewindCase.php`
- `Modules/TitanRewind/Models/RewindEvent.php`
- `Modules/TitanRewind/Models/RewindFix.php`
- `Modules/TitanRewind/Models/RewindAction.php`
- `Modules/TitanRewind/Services/RewindCaseService.php`
- `Modules/TitanRewind/Services/RewindAuditService.php`
- `Modules/TitanRewind/Services/RewindFixService.php`
- `Modules/TitanRewind/Services/RewindSuggestionService.php`
- `Modules/TitanRewind/Services/TitanPulseBridge.php`
- `Modules/TitanRewind/Observers/TitanRewindObserver.php`
- `Modules/TitanRewind/Database/Migrations/2026_05_11_000001_create_titan_rewind_cases_table.php`
- `Modules/TitanRewind/Database/Migrations/2026_05_11_000002_create_titan_rewind_events_table.php`
- `Modules/TitanRewind/Database/Migrations/2026_05_11_000003_create_titan_rewind_fixes_table.php`
- `Modules/TitanRewind/Database/Migrations/2026_05_11_000004_create_titan_rewind_actions_table.php`
- `Modules/TitanRewind/Filament/Plugin/TitanRewindPlugin.php`
- `Modules/TitanRewind/Filament/Resources/Concerns/OwnerOnlyRewindAccess.php`
- `Modules/TitanRewind/Filament/Resources/RewindCaseResource.php`
- `Modules/TitanRewind/Filament/Resources/RewindCaseResource/Pages/ListRewindCases.php`
- `Modules/TitanRewind/Filament/Resources/RewindEventResource.php`
- `Modules/TitanRewind/Filament/Resources/RewindEventResource/Pages/ListRewindEvents.php`
- `Modules/TitanRewind/Filament/Resources/RewindFixResource.php`
- `Modules/TitanRewind/Filament/Resources/RewindFixResource/Pages/ListRewindFixes.php`
- `Modules/TitanRewind/Filament/Resources/RewindActionResource.php`
- `Modules/TitanRewind/Filament/Resources/RewindActionResource/Pages/ListRewindActions.php`
- `tests/Feature/TitanRewindModuleTest.php`
- `issue-docs/issue-352.md`

## Fixes applied

- Added a new enabled `TitanRewind` module manifest targeted at the `titanpro` panel.
- Ported the rewind core tables, models, and services for cases, events, fixes, and actions.
- Added an observer-backed audit listener for tracked `Job`, `Payment`, and `User` model mutations.
- Added AI-style corrective suggestion generation for repeated rewind events plus a safe TitanPulse/TitanZero signal bridge.
- Added owner-only Filament rewind resources for cases, event timeline, fix proposals, and action log.
- Added feature coverage for mutation recording, case lifecycle flow, and owner-only resource access logic.

## Next steps

- Validate the Filament pages in-browser once the full Laravel app is running with all PHP dependencies installed.
- Reconcile the issue requirement conflict between the `titanpro` panel’s current `super_admin` gate and the owner-only resource access rule if owner UI access is required end-to-end.
