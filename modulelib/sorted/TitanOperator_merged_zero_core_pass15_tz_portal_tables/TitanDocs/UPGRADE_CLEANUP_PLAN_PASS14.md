# Pass 14 Upgrade + Cleanup Notes

## Completed
- Moved client portal runtime config to a dedicated service.
- Switched PWA runtime assets to meta-driven endpoint discovery.
- Converted legacy `enbed` access to canonical `embed` redirects.
- Redirected legacy read-only `dashboard/titan-operator/*` and `ai-chat-pro/*` paths to canonical user routes.
- Made runtime health checks inspect published assets and registered route availability.

## Next
1. Trace weakly referenced services/controllers for deletion or re-wiring.
2. Normalize remaining `dashboard.titan_operator.*` view references onto `dashboard.user.*` names where safe.
3. Replace lingering legacy labels in dashboard views and docs.
