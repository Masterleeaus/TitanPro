# Issue 421 — [FOLLOW-UP] Frontend: consume `role_ui.widget_layout` in dashboard page

## Summary

Wires the Vue dashboard page to honour the per-role widget layout stored in
`role_ui.widget_layout` (introduced in issue 195).  When a user's primary role
has an active `RoleUIProfile` the dashboard now shows only the configured
widgets in the configured order.  When no profile exists, or when
`widget_layout` is empty, the full platform-default widget set is rendered.

Source: Follows from `issue-docs/issue-195.md`

---

## Files Changed

| File | Change type | Description |
|------|-------------|-------------|
| `resources/js/types/index.d.ts` | **modified** | Added `RoleUi` interface (`role`, `hidden_nav_items`, `widget_layout`, `theme`). Added `role_ui?: RoleUi \| null` to `AppPageProps` so all pages have typed access to the shared Inertia prop. |
| `resources/js/pages/Dashboard.vue` | **modified** | Replaced placeholder "You're logged in!" content. Added `PLATFORM_DEFAULT_WIDGETS` constant (8 widget types matching `UiStudio::$allWidgets`). Added `visibleWidgets` computed property that filters and orders by `role_ui.widget_layout` when present and non-empty, falling back to the full default set. Renders each widget as a card with a `data-widget-type` attribute. Switched layout to `AppLayout` (the app's standard sidebar layout). |
| `resources/js/pages/__tests__/Dashboard.spec.ts` | **new** | Vitest / `@vue/test-utils` tests covering all five acceptance criteria: platform default renders when `role_ui` is absent; empty `widget_layout` falls back to platform default; only configured widgets render per role (finance/bookkeeper, dispatch, technician); widget order matches `widget_layout`; unknown widget types are silently skipped. |

---

## Behaviour

### Widget resolution algorithm

```
role_ui?.widget_layout           non-empty?
     │ yes                             │ no
     ▼                                 ▼
filter PLATFORM_DEFAULT_WIDGETS   return PLATFORM_DEFAULT_WIDGETS
by types in widget_layout         (all 8 widgets)
(preserve order, skip unknowns)
```

### Platform default widget set

| Type | Label |
|------|-------|
| `kpi-grid-card` | KPI Grid |
| `stat-card` | Stat Card |
| `recent-activity-card` | Recent Activity |
| `alert-notice-card` | Alert / Notice |
| `chart-bar-card` | Bar Chart |
| `chart-line-card` | Line Chart |
| `map-card` | Live Map |
| `table-card` | Data Table |

### Example role profiles

| Role | `widget_layout` | Result |
|------|----------------|--------|
| Admin / Owner | `[]` (empty) | All 8 default widgets |
| Bookkeeper / Finance | `['kpi-grid-card', 'chart-bar-card', 'chart-line-card', 'table-card']` | 4 finance widgets, in that order |
| Dispatcher | `['map-card', 'kpi-grid-card', 'recent-activity-card']` | 3 widgets, map first |
| Technician | `['stat-card', 'alert-notice-card']` | 2 minimal widgets |

---

## Tests

All 8 new test cases pass. Full suite (98 tests) passes with no regressions.

```
✓ renders all platform-default widgets when role_ui is absent
✓ falls back to platform defaults when widget_layout is empty
✓ renders only the widgets listed in widget_layout
✓ preserves the order defined in widget_layout
✓ silently skips unknown widget types in widget_layout
✓ finance / bookkeeper role sees only configured finance widgets
✓ dispatch role sees map-card first as configured
✓ technician role sees minimal widget set
```

---

## Next Steps

- [ ] Render live data inside each widget card (e.g. KPI values via an Inertia prop or API call).
- [ ] Add drag-to-reorder support in the UI so users can personalise widget order beyond the role default.
- [ ] Consume `role_ui.hidden_nav_items` in the Vue sidebar (`AppSidebar.vue`) to suppress navigation items per role.
- [ ] Consider persisting per-user widget order overrides on top of the role defaults.
