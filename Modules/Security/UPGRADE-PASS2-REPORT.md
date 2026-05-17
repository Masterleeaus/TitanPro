# Security Module Upgrade Pass 2

## Added

- Repository contract and Eloquent repository for counts, pending approvals, pending validations, and recent records.
- Workflow state enum and transition result DTO.
- Approval and validation action classes for reusable domain transitions.
- Workflow guard for permission-aware approval/validation checks.
- `/api/security/status` endpoint with JSON resource output.
- Canonical workflow definition JSON for goods in/out permits, work permits, and access cards.
- API and workflow documentation.

## Fixed

- Removed remaining migration-side `module:enable security` side effect.
- Bound repository contract in the service provider.
- Extended dashboard output with pending/recent operational data.
- Updated manifests to describe new repository, workflow, and status capabilities.

## Verification

- All PHP files passed `php -l` syntax checks.
- Duplicate fully-qualified class scan returned no duplicates.
