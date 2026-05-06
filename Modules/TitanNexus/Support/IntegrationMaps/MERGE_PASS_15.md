# Titan Nexus v15 Merge Pass

This pass continues compacting the module from preserved legacy source into native TitanNexus structure.

## Removed from shipped module
- `Support/ExtractedCode/`
- `Support/SourceArchives/`
- legacy marketplace folders/assets
- duplicated legacy Blade/LeadPilot view folders

## Promoted into native module paths
- `Http/Controllers/LegacyMarketing/`
- `Http/Requests/LegacyMarketing/`
- `Http/Resources/LegacyMarketing/`
- `Console/Commands/LegacyMarketing/`
- `Routes/LegacyMarketing/`
- `Database/migrations/legacy_marketing/`
- `Database/seeders/legacy_marketing/`
- `Services/LegacyHello/*`
- `Models/LegacyHello/*`
- `Jobs/LegacyHello/*`
- `Providers/LegacyHello/*`
- `Config/legacy_hello/*`

## Purpose
The previous ZIP carried source archives and extracted folders for traceability.
This pass turns the useful code into first-class module locations and deletes the heavy staging folders.

Generated: 2026-04-28T12:37:38.597680Z
