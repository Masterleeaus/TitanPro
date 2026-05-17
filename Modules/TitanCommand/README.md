# TitanCommand Module

TitanCommand is the workforce job lifecycle engine for TitanPro.

## Scope

- Work jobs and assignments
- Checklists and checklist items
- Evidence and inspections
- Reports and state transitions
- Work assets, permits, and links

## Registration

- Service provider: `Modules\TitanCommand\Providers\TitanCommandServiceProvider`
- Filament panel: `groundzero`

## Upgrade Notes

- Version `1.1.0` upgrades module bootstrapping to support optional routes and translations while keeping migration loading defensive.
- Existing data schema and lifecycle models remain unchanged.
