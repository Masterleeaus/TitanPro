# Titan Nexus Merge Map

This pass begins moving extracted code from Support/ExtractedCode into first-class module folders.

## Merged into canonical folders

- `System/Models` → `Models/LegacyMarketing`
- `Lead/src/Entities` → `Models/LegacyLead`
- `System/Services` → `Services/LegacyMarketing`
- `System/Tools` → `AI/Tools/Legacy`
- `System/Embedders` → `AI/Embedders`
- `System/Generators` → `AI/Generators`
- `resources/views` → `Resources/views/legacy-marketing`
- `Lead/src/Resources/views` → `Resources/views/legacy-lead`
- `leadpilot_ai/controllers` → `Http/Controllers/LegacyLeadPilot`

## Runtime bridge added

- Unified lead ingestion
- Multi-channel routing
- Legacy capability map
- Channel webhook controller
- Lead normalization job
