# BookingModule Verticals

Vertical packs customize labels, knowledge and defaults for service industries without changing booking domain logic.

## Current vertical overlays

| Vertical key | Config source | Specialization |
|---|---|---|
| `services` | `Config/verticals.php` | Generic service-booking defaults and guides under `Knowledge/guides`. |
| `cleaning` | `Config/verticals.php` | Cleaning-focused knowledge packs; lifecycle and approvals still run via shared booking Actions. |
| `maintenance` | `Config/verticals.php` | Maintenance terminology/knowledge overrides only; no duplicate booking mutation logic. |

All vertical context resolution flows through `VerticalPackService` + `ResolveVerticalContextAction`, which read module config values and return overlays without bypassing base lifecycle rules.
