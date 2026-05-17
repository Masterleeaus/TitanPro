# Security API

Base prefix: `/api/security`.

| Endpoint | Purpose |
| --- | --- |
| `GET /dashboard` | Aggregate module dashboard payload. |
| `GET /health` | Table/config health check. Returns `503` when degraded. |
| `GET /status` | Compact widget/status payload. |
| `GET /features` | Feature flags loaded from `Config/features.php`. |
| `GET /permissions` | Permission groups loaded from `Config/permissions.php`. |

All endpoints are registered behind `auth:api` in the module route file.
