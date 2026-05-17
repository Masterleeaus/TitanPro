# REAL PASS F2 — Cleaners Domain Normalization

This pass narrows the Security module toward a cleaners-first product and makes the core domain less ambiguous.

## Implemented

- Added `CleanerSite` entity for first-class cleaner site management.
- Extended cleaner registration to link to an existing site or create a site from `site_name`.
- Added `site_id` support to cleaners, cleaner site logs, access cards, work permits, and goods/equipment movement permits.
- Added API endpoints for cleaner site listing and creation.
- Updated cleaner check-in/out to store both `site_id` and human-readable site name.
- Updated web cleaner dashboard to load and show normalized sites.
- Updated migration to create `security_cleaner_sites` and safely add `site_id` to legacy operational tables.
- Added relationships across `Cleaner`, `CleanerSite`, `CleanerSiteLog`, `TrAccessCard`, `WorkPermits`, and `TrInOutPermit`.

## Product Shape After This Pass

The MVP security module now revolves around:

1. Cleaner
2. Cleaner Site
3. Cleaner Check-In/Out Log
4. Access Card
5. Work Permit
6. Goods/Equipment Movement Permit
7. Supervisor Approval
8. Basic Dashboard Metrics

## Validation

- Full PHP lint across module: passed.
- ZIP integrity verification: performed during packaging.
