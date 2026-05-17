# Issue #540 — Docs directory is not a functional module — convert or remove

## Files Changed
- `docs/00_TITAN_MONEY_SCOPE.md` — moved from `Modules/Docs/`
- `docs/07_database_schema.md` — moved from `Modules/Docs/`
- `docs/11_notifications_matrix.md` — moved from `Modules/Docs/`
- `docs/13_ai_native_module_pass3.md` — moved from `Modules/Docs/`
- `docs/14_v8_completion_summary.md` — moved from `Modules/Docs/`
- `docs/_archived_zeropay_specs/08_gateway_interface.md` — moved from `Modules/Docs/_archived_zeropay_specs/`
- `docs/_archived_zeropay_specs/09_payid_qr_spec.md` — moved from `Modules/Docs/_archived_zeropay_specs/`
- `docs/_archived_zeropay_specs/10_bank_transfer_matching.md` — moved from `Modules/Docs/_archived_zeropay_specs/`
- `docs/_archived_zeropay_specs/12_worker_payment_flow.md` — moved from `Modules/Docs/_archived_zeropay_specs/`
- `Modules/Docs/` — directory removed (was empty after moves)

## Fixes Applied
- Moved all 9 documentation files from `Modules/Docs/` to the repo-root `docs/` directory via `git mv`
- Removed the now-empty `Modules/Docs/` directory
- These are platform-level reference docs, not module code — they belong alongside the existing `docs/` tree

## Next Steps
- None — fully resolved
