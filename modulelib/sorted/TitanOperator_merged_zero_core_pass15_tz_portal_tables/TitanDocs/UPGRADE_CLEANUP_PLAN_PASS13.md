# Pass 13 Upgrade + Cleanup Plan

## Completed in this pass
- Removed donor runtime baggage from the shipping artifact.
- Canonicalised Titan Zero folder/chat AJAX paths to `dashboard/user/titanzero/*`.
- Converted legacy `client-portal-builder` pages into redirects to `client-portal/*`.
- Removed duplicated web-mounted client portal API registration.
- Normalised key upload and multichannel paths toward `dashboard/user/*`.

## Next recommended work
1. Finish normalising remaining `dashboard/titan-operator*` legacy routes.
2. Refactor voice and multi-channel menu links to the canonical user-prefixed routes.
3. Trace weakly referenced services/controllers before deletion.
4. Add route smoke tests for portal, runtime, Zero API, and voice endpoints.
5. Audit migrations/models for any stale `ext_` naming drift.
