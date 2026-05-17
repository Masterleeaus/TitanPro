# Security Module Upgrade Pass 1

## Scope
This pass upgrades the unified `Security` module from scaffolded blueprint layout to usable module infrastructure.

## Added production wiring
- Service contract: `Contracts/Services/SecurityModuleServiceInterface.php`
- Concrete service: `Services/Core/SecurityModuleService.php`
- API controller: `Http/Controllers/API/SecurityModuleController.php`
- CLI diagnostics: `security:health`
- Internal health route: `internal/security/health`
- Authenticated API routes under `/api/security/*`
- Config registration for blueprint config files
- Health-aware registry/config metadata

## Fixed issues
- Removed duplicated merged `/api/guestbooks` declarations and replaced with a single compatibility endpoint.
- Corrected work permit datatable variable naming.
- Switched unsafe work permit and goods permit update lookups to `findOrFail()`.
- Corrected `TrInOutPermit` duplicate `jam` assignment so normalized time is not overwritten.
- Corrected health-check table name from legacy merge naming to `tr_in_out_permit`.

## Preserved compatibility
Legacy view and translation namespaces remain registered:
- `trinoutpermit::`
- `trworkpermits::`
- `traccesscard::`

Legacy web routes are still available under `/account/*`.
