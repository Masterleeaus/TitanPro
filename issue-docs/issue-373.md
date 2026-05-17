# Issue 373 — Frontend role-aware sidebar hidden navigation

## Files Changed
- `resources/js/components/AppSidebar.vue`
- `resources/js/components/NavMain.vue`
- `resources/js/components/NavFooter.vue`
- `resources/js/components/__tests__/AppSidebar.spec.ts`
- `resources/js/types/index.d.ts`
- `issue-docs/issue-373.md`

## Fixes Applied
- Added explicit sidebar item keys and filtered `AppSidebar` navigation items against `page.props.role_ui?.hidden_nav_items`.
- Preserved navigation group structure by hiding `NavMain` and `NavFooter` groups when their filtered item lists are empty.
- Extended frontend page typings with `role_ui` data and optional `NavItem.key`.
- Added focused Vitest coverage to verify hidden items are suppressed and that users without a role UI profile still receive the default sidebar.

## Next Steps
- Consume `role_ui.widget_layout` in the relevant dashboard surface.
- Align any future UI Studio hidden-nav options with the exact sidebar item keys used by the Vue shell.
- Re-run full frontend build validation once PHP vendor dependencies are available for Wayfinder generation.
