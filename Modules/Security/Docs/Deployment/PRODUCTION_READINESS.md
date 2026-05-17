# Security Module Production Readiness

This pass adds deploy-time verification instead of only structural scaffold.

## Commands

```bash
php artisan security:readiness
php artisan security:readiness --json
php artisan security:cache-warm
php artisan security:install-verify
```

## API

`GET /api/security/readiness`

Returns `200` when all readiness checks pass and `503` when degraded.

## Checks

- module health/table availability
- required Laravel config values
- required Security storage/runtime folders
- required route files
- queue driver visibility

## Deployment Recommendation

Run `security:install-verify --json` after dependency install and before enabling traffic.
Run `security:cache-warm` after config/cache refresh.
