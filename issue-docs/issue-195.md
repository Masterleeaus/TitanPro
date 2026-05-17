# Issue — [UI] Build Role-Aware UI System

## Summary

Implemented a role-aware UI system that lets each organisation define per-role
theme overrides, hidden navigation items, and dashboard widget layouts.  When a
user logs in, their primary role is resolved and any matching `RoleUIProfile`
record is merged on top of the platform defaults — covering both the CSS
variables injected by the Blade shell and the `role_ui` Inertia prop consumed by
the Vue frontend.

---

## Files Changed

| File | Change type | Description |
|------|-------------|-------------|
| `database/migrations/2026_05_07_210000_create_role_ui_profiles_table.php` | **new** | Creates the `role_ui_profiles` table with columns for `organization_id`, `role`, four colour overrides, `hidden_nav_items` (JSON), and `widget_layout` (JSON). Unique constraint on `(organization_id, role)`. |
| `app/Models/RoleUIProfile.php` | **new** | Eloquent model implementing `TenantAware` + `BelongsToTenant`. Exposes `SUPPORTED_ROLES` constant, `forRole()` static helper (bypasses global scope to allow explicit org ID queries), and `themeOverrides()` helper that returns only non-null colour fields. |
| `app/Http/Middleware/HandleAppearance.php` | **modified** | After building the `$branding` array from platform settings, resolves the authenticated user's primary role and — if a `RoleUIProfile` exists — merges colour overrides into `$platformBranding` before the view share.  Result is cached under `role_ui_profile.{orgId}.{role}` with a 300-second TTL matching the platform-settings cache. |
| `app/Http/Middleware/HandleInertiaRequests.php` | **modified** | Adds a `role_ui` Inertia prop containing `role`, `hidden_nav_items`, `widget_layout`, and `theme` (colour overrides) so the Vue/Inertia frontend can conditionally filter navigation and apply widget layouts without an extra round-trip. |
| `app/Filament/Pages/UiStudio.php` | **modified** | Added `roleProfiles` and `selectedRole` Livewire state; `loadRoleProfiles()` and `defaultRoleProfile()` private helpers; `selectRole()`, `updateRoleProfile()`, `toggleNavItem()`, and `updateRoleWidgetLayout()` Livewire actions; `publish()` now persists role profiles via `RoleUIProfile::updateOrCreate` and clears the per-role cache keys. |
| `resources/views/filament/pages/ui-studio.blade.php` | **modified** | Added "Roles" to the tab strip (now Theme / Layout / Menu / Roles); added the Role Profiles tab content — role selector buttons, colour-override inputs, hidden-nav-item checkboxes, and enabled-widget checkboxes. |
| `tests/Feature/RoleUIProfileTest.php` | **new** | Feature tests covering: profile creation/retrieval, `forRole` returns null when missing, `themeOverrides` omits null fields, unique-constraint enforcement, finance user theme, dispatch user map layout, org isolation, and cache invalidation. |

---

## Behaviour Overview

### Theme override flow
1. `HandleAppearance` builds the platform branding array from `PlatformSetting`.
2. If the authenticated user has an `organization_id` and a primary role, it calls `RoleUIProfile::forRole(role, orgId)` (cached 300 s).
3. Any non-null colour fields from the profile are merged into `$platformBranding`, which is then injected as CSS custom properties (`--color-primary`, etc.) in the Blade `<head>`.
4. `HandleInertiaRequests` performs the same lookup and adds a `role_ui` top-level Inertia prop so the Vue layer can also react to theme, nav, and widget overrides.

### Fallback behaviour
If no `RoleUIProfile` exists for the user's role the org-default platform theme is used unchanged — no partial overrides are applied.

### Role profiles are org-scoped
Each `RoleUIProfile` row is tied to a single `organization_id`.  Org A's bookkeeper profile cannot be seen by or bleed into Org B.

### UI Studio — Role Profiles tab
Operators can open **UI Studio → Roles** to:
- Select a role from the supported list (admin, owner, dispatcher, bookkeeper, technician).
- Override any combination of the four brand colours.
- Check/uncheck navigation items to hide them for that role.
- Enable/disable dashboard widgets for that role's layout.
- Hit **Publish** to persist all changes (theme + layout + menu + role profiles) in one action.

---

## Role Profiles

| Role | Suggested UI style |
|------|--------------------|
| Admin / Owner | No overrides (full platform theme) |
| Bookkeeper / Finance | Finance-focused palette; invoice/payment widgets; hide Dispatch/Map |
| Dispatcher | Map-prominent; teal primary; hide Invoices/Settings from nav |
| Technician (Mobile) | High-contrast; minimal widgets; core job-status view only |

---

## Next Steps

- [ ] Frontend: consume `role_ui.hidden_nav_items` in the Vue sidebar/navigation components to actually suppress items per role.
- [ ] Frontend: consume `role_ui.widget_layout` in the dashboard page component to render role-specific widget sets.
- [ ] Consider a Filament `RoleUIProfile` resource (full CRUD table) as an alternative/supplement to the UI Studio tab.
- [ ] Add `font_heading` / `font_body` overrides per role if needed.
- [ ] Extend `SUPPORTED_ROLES` when new roles are added (e.g., `support`).
- [ ] Add an artisan command (`titan:role-ui:reset {org} {role}`) to clear profiles in production without needing the UI.
