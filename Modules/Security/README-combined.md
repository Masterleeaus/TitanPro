# Combined Security Module

This artifact combines the original Security, TrInOutPermit, TrWorkPermits, and TrAccessCard modules into a single `Modules\Security` module.

## Included domains
- Security validation for goods transfer / in-out permits.
- Security validation for work permits.
- Tenant goods in/out permit requests and approvals.
- Work permit requests, approval flows, file upload support, and PDF/export routes.
- Access card requests with card item details and PDF/export routes.

## Compatibility notes
- Public web route names and paths are preserved where possible: `security-transfer`, `security-workpermit`, `trinoutpermit`, `work-permits`, `work-permits-file`, and `card-access`.
- Legacy view namespaces are registered from inside this module: `trinoutpermit::`, `workpermits::`, and `traccesscard::`.
- PHP namespaces for merged business classes were normalized to `Modules\Security`.
- Original lower-case scaffold `routes/` files were preserved under the merged module when available, but the active nwidart module router uses `Routes/`.

## Install
Place the `Security` folder into your Laravel `Modules/` directory, refresh module discovery/autoload, and run migrations in your normal deployment flow.
